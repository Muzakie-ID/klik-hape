<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WahaService
{
    protected $baseUrl;
    protected $sessionName;
    protected $apiKey;

    public function __construct()
    {
        // Mengambil konfigurasi dari file .env
        $this->baseUrl = env('WAHA_BASE_URL', 'http://localhost:3000');
        $this->sessionName = env('WAHA_SESSION_NAME', 'default');
        $this->apiKey = env('WAHA_API_KEY', '');
    }

    /**
     * Memformat nomor WA agar selalu diawali 62 (Standar Internasional)
     * Contoh: 08123456 -> 628123456
     */
    public function formatNumber($number)
    {
        // Hilangkan karakter selain angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika diawali 0, ubah jadi 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // Jika belum ada @c.us (format WAHA), tambahkan
        if (!str_ends_with($number, '@c.us')) {
            $number = $number . '@c.us';
        }

        return $number;
    }

    /**
     * Mengirim pesan teks biasa
     */
    public function sendText($to, $message)
    {
        if (env('WAHA_ENABLED', false) == false) {
            Log::info("WAHA Disabled. Pura-puranya kirim WA ke $to: $message");
            return true;
        }

        try {
            $request = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);

            // Tambahkan header X-Api-Key jika ada
            if (!empty($this->apiKey)) {
                $request = $request->withHeaders([
                    'X-Api-Key' => $this->apiKey
                ]);
            }

            $response = $request->post("{$this->baseUrl}/api/sendText", [
                'session' => $this->sessionName,
                'chatId' => $this->formatNumber($to),
                'text' => $message,
            ]);

            if (!$response->successful()) {
                Log::error("Gagal mengirim WAHA: " . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error Exception WAHA: " . $e->getMessage());
            return false;
        }
    }
}
