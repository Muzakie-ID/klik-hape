<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Waitlist;
use App\Services\WahaService;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    protected $wahaService;

    public function __construct(WahaService $wahaService)
    {
        $this->wahaService = $wahaService;
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'wa_number' => 'required|string|min:9|max:15',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // LOGIKA ANTI-SPAM
        // Cek apakah nomor ini sudah antre untuk produk ini dan belum dikabari
        $isDuplicate = Waitlist::where('product_id', $product->id)
            ->where('wa_number', $validated['wa_number'])
            ->where('is_notified', false)
            ->exists();

        if ($isDuplicate) {
            // Jika sudah terdaftar, kembalikan dengan pesan khusus (tapi tetap ramah)
            return redirect()->back()->with('waitlist_success', 'Nomor kamu sudah masuk antrean kok Kak! Santai aja, pasti kami kabari kalau stoknya udah ready.');
        }

        // Jika belum terdaftar, simpan ke database
        Waitlist::create([
            'product_id' => $product->id,
            'customer_name' => $validated['customer_name'],
            'wa_number' => $validated['wa_number'],
            'is_notified' => false,
        ]);

        // KIRIM NOTIFIKASI WAHA (Konfirmasi Awal)
        $message = "Halo Kak *{$validated['customer_name']}*! 👋\n\n";
        $message .= "Nomor kamu sudah masuk antrean untuk *{$product->name}*.\n";
        $message .= "Nanti kalau barangnya udah ready atau ada stok masuk, kami akan langsung kabari Kakak lewat WA ini ya.\n\n";
        $message .= "Sambil nunggu, Kakak bisa cek etalase kami yang lain di " . route('home');

        // Panggil service untuk kirim WA
        $this->wahaService->sendText($validated['wa_number'], $message);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('waitlist_success', 'Sip! Nomor WA kamu sudah dicatat. Cek WA kamu sekarang ya.');
    }
}
