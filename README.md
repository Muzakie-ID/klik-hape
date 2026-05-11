# Klik Hape - MVP

Klik Hape adalah aplikasi katalog/e-commerce minimalis dengan fokus utama pada **Mobile-First Experience** dan **Leads Funneling** untuk bisnis jual-beli HP.

Berbeda dengan e-commerce tradisional yang menggunakan sistem keranjang belanja (cart), aplikasi ini dirancang khusus untuk meminimalisir proses checkout dan berfokus menangkap prospek (leads) saat produk sedang kosong (Pre-Order/Waitlist), kemudian melakukan *broadcast* WhatsApp secara otomatis saat stok kembali tersedia.

## Fitur Utama

- **Katalog Mobile-First:** Tampilan beranda dan detail produk yang dirancang khusus menyerupai aplikasi native di HP.
- **Direct Checkout:** Tombol pembelian langsung mengarah ke toko Shopee atau negosiasi via chat WhatsApp Admin.
- **Smart Waitlist System:** Saat stok barang 0, tombol beli otomatis berubah menjadi form "Ingatkan Saya". Pengunjung cukup memasukkan Nama dan Nomor WA.
- **Auto-Reply WhatsApp:** Integrasi dengan API WhatsApp (WAHA) untuk mengirim pesan konfirmasi otomatis begitu pengunjung masuk antrean.
- **Broadcast Restock:** Saat admin meng-update stok produk dari 0 menjadi tersedia, sistem akan otomatis mengirim *WhatsApp Blast* ke semua antrean untuk produk tersebut.
- **Dashboard Analytics:** Grafik interaktif (berbasis Chart.js) untuk memantau pertumbuhan prospek dan "Sinyal Kulakan" (top produk kosong yang paling banyak diantre).
- **Auto Compression Images:** Fitur upload foto produk dengan konversi otomatis ke `.webp` dan kompresi cerdas untuk menjaga kecepatan loading web, mendukung format dari iPhone (`.heic`).

## Tech Stack

- **Backend:** Laravel 11.x
- **Frontend UI:** Tailwind CSS, Alpine.js (TALL Stack approach)
- **Database:** MySQL
- **WhatsApp API:** WAHA (WhatsApp HTTP API)
- **Image Processing:** Intervention Image
- **Deployment:** Docker & Docker Compose

## Panduan Instalasi (Development)

1. Clone repositori ini:
   ```bash
   git clone https://github.com/Muzakie-ID/klik-hape.git
   cd klik-hape
   ```

2. Install dependensi PHP dan Node.js:
   ```bash
   composer install
   npm install
   npm run build
   ```

3. Salin file environment dan atur database Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan migrasi dan buat admin default:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Kredensial default - Email: admin@klik-hape.com | Password: password123)*

5. Buat tautan storage untuk gambar:
   ```bash
   php artisan storage:link
   ```

6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

## Panduan Deployment (Docker / VPS)

Proyek ini sudah dilengkapi dengan `Dockerfile` dan `docker-compose.yml` untuk lingkungan produksi.

1. Di server VPS Anda, clone repositori ini.
2. Salin dan konfigurasi file `.env` (Pastikan mengisi `APP_URL`, `DB_PASSWORD`, dan konfigurasi `WAHA_`).
3. Jalankan Docker Compose:
   ```bash
   docker-compose up -d --build
   ```

*Catatan: Konfigurasi docker bawaan disetel untuk terhubung dengan jaringan/database MySQL eksternal bernama `db_master_shared`. Jika Anda menggunakan konfigurasi lokal, Anda dapat menyesuaikan file `docker-compose.yml`.*

## Konfigurasi WhatsApp Bot (WAHA)

Agar fitur "Ingatkan Saya" dan Broadcast berfungsi, Anda memerlukan VPS yang menjalankan WAHA. 

Buka file `.env` dan atur bagian berikut:
```env
WAHA_ENABLED=true
WAHA_BASE_URL=https://waha.domainanda.com
WAHA_SESSION_NAME=default
WAHA_API_KEY=rahasia
```

## Panduan Perintah Docker (Cheatsheet)

Berikut adalah beberapa perintah berguna untuk mengelola aplikasi di dalam container Docker:

**1. Masuk & Menjalankan Perintah (Exec)**
Untuk mengeksekusi perintah (seperti artisan atau composer) di dalam container, gunakan `docker compose exec`:
```bash
# Migrasi database
docker compose exec app php artisan migrate

# Install atau update package composer
docker compose exec app composer install

# Masuk ke dalam terminal container (bash)
docker compose exec app bash
```

**2. Memperbaiki Perizinan (Permissions)**
Jika saat aplikasi berjalan terdapat *error permission denied* pada saat upload file gambar, penulisan log, atau *asset* (CSS/JS) terblokir, Anda bisa menjalankan perintah perbaikan kepemilikan dan hak akses (khususnya untuk folder *storage*, *bootstrap/cache*, dll):
```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build
docker compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build
```

**3. Membersihkan Cache (Clear Cache)**
Seringkali setelah mengubah file `.env` atau *views*, perubahan tidak langsung terlihat di *production*. Bersihkan *cache* aplikasi dengan:
```bash
docker compose exec app php artisan optimize:clear
```
*(Perintah di atas akan menghapus cache config, route, view, dan cache aplikasi secara bersamaan).*

**4. Melihat Log Error Container**
Jika terdapat error 500 dan ingin melihat *live log* dari *container*:
```bash
docker compose logs -f app
```

## Lisensi

Proyek ini dibuat khusus untuk keperluan internal bisnis **Klik Hape**.
