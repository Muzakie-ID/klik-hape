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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        $filename = Str::uuid() . '.webp';
        $path = 'products/' . $filename;

        // Buat instance ImageManager dengan GD driver (Intervention Image v4 API)
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        // Resize jika terlalu besar (max 1200px)
        $image->resize(1200, 1200, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Simpan sebagai webp kualitas 80%
        $image->toWebp(80)->save(Storage::disk('public')->path($path));

        return $path;
    }

    public function index()
    {
        $products = Product::with(['media' => function ($query) {
            $query->where('is_primary', true);
        }])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $product = Product::create([
            'slug' => $slug,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'shopee_link' => $validated['shopee_link'] ?? null,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $this->processAndStoreImage($image);
                ProductMedia::create([
                    'product_id' => $product->id,
                    'file_path' => $path,
                    'media_type' => 'image',
                    'is_primary' => $index === 0 ? true : false,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product) { }
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $oldStock = $product->stock;
        $newStock = $validated['stock'];

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'shopee_link' => $validated['shopee_link'] ?? null,
            'stock' => $newStock,
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            $hasPrimary = $product->media()->where('is_primary', true)->exists();
            $currentMaxOrder = $product->media()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
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

        $broadcastCount = 0;
        if ($oldStock == 0 && $newStock > 0 && $validated['status'] == 'published') {
            $waitingList = Waitlist::where('product_id', $product->id)
                                 ->where('is_notified', false)->get();
            $broadcastCount = $waitingList->count();
            if ($broadcastCount > 0) {
                foreach ($waitingList as $waitlist) {
                    $message = "Halo Kak *{$waitlist->customer_name}*! 🎉\n\n";
                    $message .= "Kabar gembira! HP *{$product->name}* yang Kakak tunggu-tunggu sekarang sudah *READY STOCK* lho!\n\n";
                    $message .= "Cek detail lengkapnya di sini:\n" . route('product.show', $product->slug) . "\n\n";
                    if ($product->shopee_link) {
                        $message .= "Atau via Shopee:\n{$product->shopee_link}\n\n";
                    }
                    $message .= "Terima kasih! 😊";
                    $this->wahaService->sendText($waitlist->wa_number, $message);
                    $waitlist->update(['is_notified' => true, 'notified_at' => now()]);
                    sleep(1);
                }
            }
        }

        $msg = 'Produk berhasil diperbarui!';
        if ($broadcastCount > 0) {
            $msg .= " WA Blast ke {$broadcastCount} orang.";
        }
        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    public function destroy(Product $product)
    {
        foreach ($product->media as $media) {
            if (Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
