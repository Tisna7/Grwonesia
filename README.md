# <p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Grownesia Logo"><br>🌱 Grownesia</p>

---

<p align="center">
  <strong>Grownesia</strong> adalah platform digital multi-role terintegrasi berbasis AI yang dirancang untuk mempercepat digitalisasi, tata kelola keuangan, logistik, pemasaran, dan pembinaan bagi Usaha Mikro, Kecil, dan Menengah (UMKM) di Indonesia. Platform ini menjembatani hubungan antara <strong>Pembeli</strong>, <strong>Pelaku Usaha (UMKM)</strong>, <strong>Pemerintah Daerah (Dinas Koperasi & UMKM)</strong>, dan <strong>Administrator</strong> dalam satu ekosistem terpadu.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-v12.0-red?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/TailwindCSS-v4.0-38bdf8?style=for-the-badge&logo=tailwindcss" alt="Tailwind v4">
  <img src="https://img.shields.io/badge/Gemini--AI-Enabled-blue?style=for-the-badge&logo=google-gemini" alt="Google Gemini">
  <img src="https://img.shields.io/badge/Midtrans-Integrated-orange?style=for-the-badge" alt="Midtrans">
  <img src="https://img.shields.io/badge/Biteship-Integrated-green?style=for-the-badge" alt="Biteship">
</p>

---

## 🎯 Visi & Solusi Grownesia

UMKM di Indonesia seringkali menghadapi tantangan dalam hal **pencatatan keuangan**, **pemasaran digital**, **akses logistik**, serta **pembinaan dari pemerintah**. Grownesia hadir dengan solusi:
1. **Data-Driven & AI Assisted**: Menggunakan kecerdasan buatan (Google Gemini API) untuk melakukan optimasi produk, pembuatan konten marketing, pembinaan interaktif, hingga simulasi kebijakan pemerintah.
2. **Logistik & Pembayaran Tanpa Batas**: Integrasi langsung dengan **Midtrans** untuk pembayaran multi-channel otomatis dan **Biteship** untuk perhitungan ongkir real-time dan pelacakan kurir terpadu.
3. **Otomasi Pemasaran (Omnichannel)**: Membantu UMKM mempublikasikan konten langsung ke **Instagram** (Meta Graph API) dan melakukan broadcast penawaran langsung ke **WhatsApp** pelanggan lewat gateway mandiri.

---

## 👥 Alur Pengguna & Fitur Ungkapan Berdasarkan Role

Grownesia mengimplementasikan sistem multi-role dinamis yang memungkinkan pengguna beralih peran atau mengelola fitur khusus:

### 1. Pembeli / User (`role: user`)
*   **Belanja Produk UMKM**: Eksplorasi katalog produk UMKM lokal berdasarkan kategori.
*   **Smart Shopping Cart & Checkout**: Keranjang belanja interaktif terintegrasi.
*   **Integrasi Midtrans**: Pembayaran aman menggunakan Virtual Account, QRIS, e-wallet dengan verifikasi pembayaran otomatis (Midtrans Webhook Callback).
*   **Integrasi Logistik Biteship**: Pencarian area dinamis (kecamatan/kota), kalkulasi ongkos kirim real-time dari kurir populer, serta halaman pelacakan status pengiriman (*tracking*) interaktif.
*   **Asisten Belanja AI**: 
    *   *AI Assistant*: Chatbot personal shopper untuk merekomendasikan produk.
    *   *AI Gift Recommender*: Rekomendasi kado terbaik berdasarkan preferensi dan anggaran belanja.
    *   *AI Product Comparison*: Bandingkan spesifikasi dan nilai produk secara otomatis sebelum membeli.
*   **Ulasan & Favorit**: Berikan rating bintang, komentar terverifikasi setelah pembelian, dan simpan produk favorit.

### 2. Pelaku Usaha / UMKM (`role: business`)
*   **Dashboard Keuangan & Metrik**: Pemantauan penjualan harian, tren pendapatan, laba bersih, HPP, serta analisis Break-Even Point (BEP) dinamis berbasis grafik (Chart.js) setelah menentukan *monthly fixed cost*.
*   **Manajemen Produk & AI Optimizer**:
    *   *AI Photo Enhancement*: Optimasi tampilan foto produk menggunakan AI.
    *   *AI Description Generator*: Pembuatan deskripsi produk otomatis yang memikat calon pembeli.
    *   *AI SEO Generator*: Pembuatan judul dan tag SEO-friendly untuk meningkatkan peringkat Google search.
*   **Manajemen Inventori**: Pemantauan stok dengan indikator peringatan jika stok berada di bawah batas minimum (*low stock alerts*).
*   **AI Business Coach**: Chatbot pembimbing bisnis interaktif yang menganalisis metrik usaha secara real-time dan memberikan saran strategis.
*   **AI Marketing Generator**: Hasilkan salinan iklan untuk WhatsApp, Instagram, Facebook, dan Twitter dalam sekali klik.
*   **WhatsApp Marketing Suite**: Mengirim pesan broadcast promosi ke database pelanggan secara massal menggunakan WhatsApp Gateway mandiri.
*   **Instagram Suite**: Publikasi foto produk dan salinan promosi langsung ke akun Instagram Business terhubung via Meta Graph API, lengkap dengan info statistik follower.

### 3. Pemerintah Daerah / Dinas (`role: government`)
*   **Analisis Agregat Regional**: Pemantauan data spasial performa UMKM berdasarkan kota/kabupaten dan kategori sektor usaha di Jawa Barat.
*   **Simulasi Kebijakan Ekonomi AI**: Prediksi dampak sebelum menerapkan kebijakan baru (seperti insentif subsidi, pajak, pelatihan digital) terhadap profitabilitas rata-rata UMKM.
*   **Manajemen Program Kerja**: Pembuatan program pembinaan, pelatihan, atau pameran bagi UMKM terpilih.
*   **AI Program Recommender**: Rekomendasi otomatis program dinas yang paling cocok untuk kelompok UMKM tertentu berdasarkan analitik performa mereka.

### 4. Admin Sistem (`role: admin`)
*   **Verifikasi Legalitas Bisnis**: Meninjau dokumen pendaftaran usaha UMKM dan mengaktifkan status verifikasi (Verified Partner Badge).
*   **AI Center Monitor**: Memantau konsumsi token, log request sukses/gagal, durasi eksekusi API, serta efisiensi model Gemini AI yang digunakan di seluruh platform.

---

## 🛠️ Tech Stack & Arsitektur Sistem

### Laravel Monorepo & Services
*   **Framework Utama**: Laravel 12 (PHP 8.2+)
*   **Engine Frontend**: Vite + Blade + TailwindCSS v4 + Alpine.js (reactive component interactions)
*   **Visualisasi Data**: Chart.js untuk grafik analitik keuangan dan regional
*   **Gateway WhatsApp**: NodeJS microservice berbasis library `@whiskeysockets/baileys` yang berjalan di port 3010.

### Integrasi Pihak Ketiga
1.  **Google Gemini API (Generative AI)**: `gemini-3.1-flash-lite` (untuk tugas analitik berat) & `gemini-2.5-flash-lite` (untuk pengerjaan cepat seperti SEO tag & deskripsi produk).
2.  **Midtrans API**: Transaksi e-payment sandbox dengan sistem notifikasi HTTP Post webhook.
3.  **Biteship API**: Cek tarif kurir domestik terlengkap, area search autocomplete, dan tracking AWB pengiriman.
4.  **Meta Graph API**: Posting media dan caption otomatis ke akun Instagram Business UMKM.
5.  **Google OAuth (Socialite)**: Login praktis menggunakan akun Google.

---

## 📂 Struktur Direktori Utama

```text
grownesia/
├── app/
│   ├── Enums/                 # Enum untuk OrderStatus, UserRole, dll.
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/         # Controller panel Admin
│   │       ├── Business/      # Controller panel UMKM / Pelaku Usaha
│   │       ├── Government/    # Controller panel Dinas Koperasi & UMKM
│   │       └── User/          # Controller pembeli, checkout, cart
│   ├── Models/                # Eloquent ORM Models
│   └── Services/              # Logika Bisnis & Integrasi API
│       ├── Ai/                # AI Agent, Business Coach, Policy Simulator
│       ├── BiteshipService.php# Integrasi logistik Biteship
│       ├── MidtransService.php# Integrasi pembayaran Midtrans
│       ├── Instagram/         # Layanan meta content publishing
│       └── WhatsApp/          # Integrasi gateway & bot sesi WA
├── config/                    # Laravel configuration files
├── database/
│   ├── migrations/            # Skema database relasional
│   └── seeders/               # Seeder data dummy & akun demo lengkap
├── docs/                      # Dokumentasi teknis tambahan (Instagram Setup)
├── resources/
│   ├── css/app.css            # Styling dengan Tailwind CSS v4
│   ├── js/                    # Client-side scripts & Chart configs
│   └── views/                 # Blade layouts dan templates
├── routes/
│   └── web.php                # Rute web terpusat dengan middleware role-based
├── wa-gateway/                # [Microservice] NodeJS WhatsApp Gateway Baileys
└── vite.config.js             # Vite bundler configuration
```

---

## 🔑 Akun Demo (Credentials)

Database seeder secara otomatis menyediakan beberapa akun uji coba dengan data historis transaksi 90 hari terakhir:

| Role | Nama Akun | Email | Password | Keterangan / Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| **User (Buyer)** | Budi Santoso | `budi@grownesia.id` | `password` | Akun pembeli ritel, memiliki keranjang, notifikasi, dan alamat Jakarta. |
| **Business (UMKM)** | Kopi & Keripik Nusantara | `demo@grownesia.test` | `password` | UMKM Sukabumi dengan data produk makanan/minuman dan data penjualan melimpah. |
| **Government** | Dinas Koperasi & UMKM Jabar | `gov@grownesia.test` | `password` | Akun kedinasan dengan akses simulator kebijakan ekonomi dan pemantauan regional. |
| **Super Admin** | Super Admin Grownesia | `admin@grownesia.test` | `password` | Mengakses dashboard verifikasi UMKM baru dan log monitoring AI Center. |
| **Business 2** | Batik Priangan Asli | `umkm2@grownesia.test` | `password` | UMKM Batik asal Kota Bandung. |
| **Business 3** | Kerajinan Bambu Tasik | `umkm3@grownesia.test` | `password` | UMKM anyaman bambu asal Tasikmalaya. |

---

## 🚀 Panduan Instalasi & Pengaturan

Ikuti langkah-langkah di bawah ini untuk menjalankan Grownesia di komputer lokal Anda:

### 1. Prasyarat Sistem
*   PHP >= 8.2 (dilengkapi ekstensi pdo, gd, curl, mbstring, zip)
*   Composer
*   Node.js (LTS rekomendasikan) & NPM
*   Database Server (MySQL / MariaDB / SQLite)

### 2. Kloning dan Setup Awal
Kloning repositori dan masuk ke direktori utama:
```bash
git clone <repository-url> grownesia
cd grownesia
```

### 3. Konfigurasi Environment File
Salin `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka `.env` dan atur detail database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grownesia
DB_USERNAME=root
DB_PASSWORD=your_password
```

Lalu lengkapi konfigurasi API yang dibutuhkan:
```env
# Integrasi Google Gemini API
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-3.1-flash-lite
GEMINI_MODEL_LITE=gemini-2.5-flash-lite

# WhatsApp Gateway (Driver: log atau baileys)
WA_DRIVER=baileys
WA_GATEWAY_URL=http://localhost:3010
WA_GATEWAY_TOKEN=your_secure_random_token_here

# Biteship Logistics API
BITESHIP_API_KEY=your_biteship_api_key_here

# Midtrans Payment Gateway
MIDTRANS_CLIENT_KEY=your_midtrans_client_key_here
MIDTRANS_SERVER_KEY=your_midtrans_server_key_here

# Instagram Content Publishing (Opsional)
IG_BUSINESS_ID=your_instagram_business_id
IG_ACCESS_TOKEN=your_meta_long_lived_token
```

### 4. Setup Laravel
Jalankan perintah pintas `setup` yang sudah dikonfigurasi di `composer.json` untuk memasang dependensi, membuat app key, menjalankan migrasi database beserta seeder, dan membangun aset front-end:
```bash
composer run-script setup
```
*(Atau lakukan secara manual: `composer install`, `php artisan key:generate`, `php artisan migrate:fresh --seed`, `npm install`, `npm run build`)*

---

## 📱 Menjalankan Microservice WhatsApp Gateway

WhatsApp Gateway dikembangkan menggunakan NodeJS + Baileys. Layanan ini harus diaktifkan secara terpisah di port `3010`.

1.  Masuk ke direktori `wa-gateway`:
    ```bash
    cd wa-gateway
    ```
2.  Salin `.env.example` dan konfigurasikan:
    ```bash
    cp .env.example .env
    ```
    Isi `PORT=3010`, `API_TOKEN` (samakan dengan `WA_GATEWAY_TOKEN` di `.env` Laravel), dan `WEBHOOK_URL` (`http://localhost:8000/whatsapp/webhook`).
3.  Instal dependensi dan jalankan gateway:
    ```bash
    npm install
    npm start
    ```
4.  Terminal akan memunculkan **QR Code**. Buka aplikasi WhatsApp di HP Anda -> **Perangkat Tertaut (Linked Devices)** -> **Tautkan Perangkat** lalu scan QR Code di terminal tersebut untuk menghubungkan nomor WA UMKM Anda.

---

## 💻 Menjalankan Server Pengembangan (Lokal)

Kembali ke direktori root `grownesia`, jalankan perintah berikut untuk mengaktifkan server Laravel (`php artisan serve`), antrian email/WA queue worker, pembaca log `pail`, serta server HMR Vite secara bersamaan dalam satu terminal:

```bash
npm run dev
```

Server Anda akan berjalan di `http://localhost:8000`. Silakan buka di browser dan masuk menggunakan salah satu [Akun Demo](#-akun-demo-credentials) di atas.

---

## 📸 Panduan Fitur Khusus & Dokumentasi Tambahan

*   **Setup Meta Graph API / Instagram**: Silakan baca panduan lengkap cara memperoleh ID Bisnis Instagram dan Akses Token Jangka Panjang di berkas:
    👉 [Panduan Instagram Setup](docs/instagram-setup.md)
*   **Pengujian Kode**: Anda dapat menjalankan unit/feature test yang disediakan dengan menjalankan:
    ```bash
    composer test
    ```

---

## 📄 Lisensi

Grownesia dikembangkan sebagai platform berlisensi terbuka di bawah [MIT License](LICENSE).
