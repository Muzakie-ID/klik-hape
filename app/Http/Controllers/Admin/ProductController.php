<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Waitlist;
use App\Services\WahaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    protected $wahaService;

    public function __construct(WahaService $wahaService)
    {
        $this->wahaService = $wahaService;
    }

    /**
     * Mengkompres dan menyimpan gambar
     */
    private function processAndStoreImage($file)
    {
        // Buat nama file unik dengan ekstensi webp
        $filename = Str::uuid() . '.webp';
        $path = 'products/' . $filename;

        // Buat instance image dari file yang diupload
        $image = Image::read($file);

        // Resize gambar jika ukurannya terlalu besar (Maksimal lebar/tinggi 1200px)
        // Aspect ratio akan tetap dipertahankan
        $image->scaleDown(width: 1200, height: 1200);

        // Simpan gambar dalam format webp dengan kualitas 80%
        Storage::disk('public')->put($path, $image->toWebp(80)->toString());

        return $path;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['media' => function ($query) {
            $query->where('is_primary', true);
        }])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Generate unique slug
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create product
        $product = Product::create([
            'slug' => $slug,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'shopee_link' => $validated['shopee_link'] ?? null,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        // Handle image uploads with Compression
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Proses kompresi gambar
                $path = $this->processAndStoreImage($image);

                ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $path,
                    'media_type' => 'image',
                    'is_primary' => $index === 0 ? true : false, // First image is primary
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Simpan status stok lama untuk mengecek apakah ada restock dari 0 ke > 0
        $oldStock = $product->stock;
        $newStock = $validated['stock'];

        // Update product basic info
        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'shopee_link' => $validated['shopee_link'] ?? null,
            'stock' => $newStock,
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        // Handle new image uploads (append to existing) with compression
        if ($request->hasFile('images')) {
            $hasPrimary = $product->media()->where('is_primary', true)->exists();
            $currentMaxOrder = $product->media()->max('sort_order') ?? -1;

            foreach ($request->file('images') as $index => $image) {
                // Proses kompresi gambar
                $path = $this->processAndStoreImage($image);

                ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $path,
                    'media_type' => 'image',
                    'is_primary' => (!$hasPrimary && $index === 0) ? true : false,
                    'sort_order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        // TRIGGER BROADCAST WAHA JIKA RESTOCK DARI 0 KE > 0
        $broadcastCount = 0;
        if ($oldStock == 0 && $newStock > 0 && $validated['status'] == 'published') {

            // Cari semua orang yang ngantri produk ini dan belum dikabari
            $waitingList = Waitlist::where('product_id', $product->id)
                                 ->where('is_notified', false)
                                 ->get();

            $broadcastCount = $waitingList->count();

            if ($broadcastCount > 0) {
                foreach ($waitingList as $waitlist) {

                    // Siapkan pesan Broadcast
                    $message = "Halo Kak *{$waitlist->customer_name}*! 🎉\n\n";
                    $message .= "Kabar gembira! HP *{$product->name}* yang Kakak tunggu-tunggu sekarang sudah *READY STOCK* lho!\n\n";
                    $message .= "Cek detail lengkapnya dan buruan order sebelum kehabisan lagi di sini:\n";
                    $message .= route('product.show', $product->slug) . "\n\n";

                    if ($product->shopee_link) {
                        $message .= "Atau mau langsung checkout via Shopee? Klik di sini:\n{$product->shopee_link}\n\n";
                    }

                    $message .= "Terima kasih sudah setia menunggu! 😊";

                    // Kirim Pesan
                    $this->wahaService->sendText($waitlist->wa_number, $message);

                    // Update status bahwa orang ini sudah dikabari
                    $waitlist->update([
                        'is_notified' => true,
                        'notified_at' => now()
                    ]);

                    // Jeda sebentar agar tidak disangka spam oleh sistem WhatsApp (1 detik)
                    sleep(1);
                }
            }
        }

        $successMessage = 'Produk berhasil diperbarui!';
        if ($broadcastCount > 0) {
            $successMessage .= " Dan berhasil mengirim WA Blast ke {$broadcastCount} orang yang mengantre.";
        }

        return redirect()->route('admin.products.index')->with('success', $successMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated files from storage
        foreach ($product->media as $media) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
