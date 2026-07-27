# Setup Instagram Content Publishing (Meta Graph API)

Panduan mendapatkan `IG_BUSINESS_ID` dan `IG_ACCESS_TOKEN` supaya Grownesia bisa posting foto langsung ke Instagram.

## Prasyarat (sekali saja, di sisi akun)

1. **Akun Instagram harus tipe Business/Professional**
   Instagram → Settings → Account type and tools → Switch to professional account → Business.
2. **Hubungkan IG ke sebuah Facebook Page**
   Instagram → Settings → Business tools → Connect a Facebook Page (buat Page baru kalau belum punya).
3. **Buat Meta App** di https://developers.facebook.com
   - My Apps → Create App → pilih tipe **Business**.
   - Di dashboard app, tambahkan produk **Instagram Graph API** dan **Facebook Login**.

> Selama kamu adalah admin/developer/tester dari app tersebut, kamu bisa posting ke akun IG milikmu sendiri **tanpa perlu App Review** (mode Development). App Review baru dibutuhkan kalau user lain (UMKM lain) mau menghubungkan akun mereka.

## Ambil token & ID (via Graph API Explorer)

1. Buka https://developers.facebook.com/tools/explorer
2. Pilih app kamu di dropdown kanan atas.
3. **Generate Access Token** — saat diminta izin, centang:
   - `instagram_basic`
   - `instagram_content_publish`
   - `pages_show_list`
   - `business_management`
4. Dapatkan **Page ID**: jalankan query `me/accounts` → salin `id` dari Page yang terhubung ke IG.
5. Dapatkan **IG Business ID**: jalankan query `{page-id}?fields=instagram_business_account` → salin `instagram_business_account.id`.
   → ini nilai untuk `IG_BUSINESS_ID`.
6. **Tukar token jadi long-lived** (±60 hari, token explorer hanya ±1 jam):

   ```
   https://graph.facebook.com/v21.0/oauth/access_token?grant_type=fb_exchange_token&client_id={APP_ID}&client_secret={APP_SECRET}&fb_exchange_token={TOKEN_PENDEK}
   ```

   Buka URL itu di browser (ganti placeholder; APP_ID & APP_SECRET ada di App Settings → Basic). Salin `access_token` dari respons → ini nilai untuk `IG_ACCESS_TOKEN`.

7. Isi keduanya di `.env` Laravel:

   ```
   IG_BUSINESS_ID=1784xxxxxxxxxxx
   IG_ACCESS_TOKEN=EAAxxxxxxx...
   ```

Buka halaman **Instagram** di dashboard Business — kalau kartu status menampilkan username + jumlah follower, koneksi berhasil.

## Batasan penting

- **Foto harus URL publik.** Meta mengunduh foto dari URL yang kita kirim — `localhost` tidak bisa. Untuk development, jalankan tunnel lalu sesuaikan `APP_URL`:

  ```bash
  ngrok http 8000
  ```

  atau `cloudflared tunnel --url http://localhost:8000`.
- Format yang didukung publish foto: **JPEG** (PNG sering ditolak Meta — unggah foto produk sebagai JPG).
- Rate limit: maksimal **50 post per akun per 24 jam** (kuota API `content_publishing_limit`).
- Token long-lived kedaluwarsa ±60 hari — perbarui dengan mengulang langkah 6 (atau otomasi refresh di masa depan).
