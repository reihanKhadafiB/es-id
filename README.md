# Sistem Pencatatan Penjualan — Usaha Es (Es Teh, Pop Ice, Teh Jus)

Sistem pencatatan penjualan untuk klien usaha minuman es (MVP). Fokus pada pencatatan transaksi kasir secara cepat dan laporan rekap akurat.

## Status Proyek: MVP Selesai (100%) ✅

Proyek ini telah dikembangkan sepenuhnya dan siap digunakan di production (cPanel/Shared Hosting). 
Berikut adalah fitur-fitur yang sudah berjalan:
- **PWA Ready**: Aplikasi dapat diinstal ke layar utama (*Home Screen*) HP (Android & iOS).
- **Responsive Mobile UI**: Desain antarmuka dioptimalkan untuk kasir menggunakan smartphone, dilengkapi *Bottom Navigation Bar*.
- **Manajemen Kategori & Produk**: Mengatur produk yang dijual secara dinamis.
- **Kasir (Catat Transaksi)**: Keranjang belanja real-time, dikelompokkan berdasarkan kategori. Terdapat *empty state* untuk mencegah *error*.
- **Riwayat Penjualan**: Histori lengkap transaksi dengan fitur *Void* (Pembatalan tanpa menghapus data).
- **Laporan Otomatis**: Rekap pendapatan (Harian/Mingguan/Bulanan) lengkap dengan **Breakdown Produk Terjual**.

## Stack Teknologi

| Layer      | Pilihan                        |
|------------|---------------------------------|
| Framework  | Laravel 13 (`laravel/vue-starter-kit`, PHP ^8.3) |
| Frontend   | Vue 3 + Inertia.js ^3.0 (SPA feel) |
| Styling    | Tailwind CSS v4                 |
| Auth       | Laravel Fortify (single user)   |
| Database   | PostgreSQL                      |
| Delivery   | Progressive Web App (PWA)       |

## Skema Database

- **users**: Satu akun Admin saja.
- **categories**: Menyimpan kategori produk (mis. "Minuman Dingin").
- **products**: Menyimpan daftar menu beserta harga.
- **transactions**: Data header transaksi (tanggal, total tagihan, status `completed`/`voided`).
- **transaction_items**: Data detail item yang dibeli (*snapshot* nama & harga untuk menjaga integritas data histori).

## Cara Menjalankan di Lokal (Development)

1. Pastikan PostgreSQL berjalan dan sesuaikan kredensial di file `.env`.
2. Install dependensi PHP dan Node:
   ```bash
   composer install
   npm install
   ```
3. Jalankan migrasi dan buat akun Admin (karena fitur register publik dinonaktifkan):
   ```bash
   php artisan migrate
   php artisan tinker
   # Jalankan di tinker:
   # App\Models\User::create(['name' => 'Admin', 'email' => 'admin@es.id', 'password' => bcrypt('password')]);
   ```
4. Jalankan server backend dan frontend sekaligus dengan satu perintah:
   ```bash
   npm run serve
   ```
5. Buka `http://localhost:8000` di browser dan login.

## Panduan Deployment (Shared Hosting cPanel)

1. Lakukan *build* aset frontend secara lokal:
   ```bash
   npm run build
   ```
2. Upload seluruh folder project (termasuk folder `public/build/`) ke server hosting via ZIP/FTP.
   *(Folder `node_modules/` tidak perlu diupload)*
3. Ubah pengaturan *Document Root* domain Anda untuk mengarah ke direktori `public/`.
4. Sesuaikan file `.env` di server (Koneksi Database PostgreSQL).
5. Jalankan migrasi di server hosting (jika cPanel memiliki fitur terminal):
   ```bash
   php artisan migrate
   ```
6. **Syarat PWA**: Pastikan website sudah menggunakan HTTPS / SSL aktif agar *Service Worker* dapat berjalan dan tombol "Install App" muncul di browser pengunjung.
