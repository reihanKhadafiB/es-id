# ES.ID - Mobile Banking Web Application

ES.ID adalah aplikasi web dengan desain _mobile-banking_ premium yang berfokus pada manajemen keuangan, transaksi, dan keamanan tingkat tinggi. Dibangun menggunakan teknologi terkini, aplikasi ini menawarkan pengalaman pengguna yang responsif (_seamless_), cepat, dan aman, termasuk dukungan penuh untuk autentikasi tanpa kata sandi (_Passkeys / Biometrik_).

## 🚀 Fitur Utama

- **Autentikasi Modern:** Login sangat aman menggunakan Passkeys (Face ID / Touch ID / Sidik Jari) via `laravel/passkeys` yang terintegrasi dengan Fortify.
- **Desain Premium:** Antarmuka responsif bergaya M-Banking dengan efek visual premium menggunakan Tailwind CSS dan komponen Reka UI.
- **Manajemen Keuangan:** Lacak transaksi harian, pengeluaran, dan statistik pendapatan dengan dasbor yang intuitif.
- **Single Page Application (SPA):** Navigasi super cepat tanpa _reload_ halaman menggunakan Inertia.js v3.
- **Kustomisasi Cerdas:** Pengaturan profil, ubah kata sandi, hingga penanganan status _error_ khusus (404, 500, dll.) yang disesuaikan dengan tema aplikasi.
- **Laporan & Ekspor:** Fitur ekspor laporan data keuangan yang efisien.

## 🛠️ Spesifikasi & Tech Stack

Proyek ini dikembangkan dengan kerangka kerja dan pustaka modern:

- **Backend:** Laravel 13 (PHP 8.3+)
- **Frontend:** Vue 3 (Composition API), Inertia.js v3
- **Styling & UI:** Tailwind CSS v3/v4, Reka UI, Lucide Icons
- **Autentikasi & Keamanan:** Laravel Fortify & Laravel Passkeys
- **Pengujian (Testing):** Pest PHP & Larastan
- **Code Quality:** Laravel Pint (Linter) & PHPStan (Static Analysis)
- **Tooling:** Vite, Wayfinder (Auto-generated Laravel Routes for frontend)

## 📦 Persyaratan Sistem (Prerequisites)

Pastikan sistem operasi lokal Anda telah memenuhi persyaratan perangkat lunak berikut:

- PHP >= 8.3
- Composer >= 2.0
- Node.js >= 20.x beserta `npm`
- SQLite (Secara _default_) atau MySQL / PostgreSQL

## 🔧 Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk mempersiapkan proyek ini di lingkungan pengembangan (_development_) lokal Anda:

1. **Kloning Repositori**

    ```bash
    git clone <URL_REPOSITORY_ANDA>
    cd es-id
    ```

2. **Instalasi Dependensi PHP & JavaScript**

    ```bash
    composer install
    npm install
    ```

3. **Pengaturan Environment (Konfigurasi Aplikasi)**
   Salin _file_ `.env.example` menjadi `.env`, lalu hasilkan _application key_ untuk enkripsi:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database**
   Secara _default_, proyek ini menggunakan SQLite. Buat _file_ databasenya secara manual lalu jalankan _migration_:
    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```

## 🚀 Cara Menjalankan Aplikasi

Untuk menjalankan aplikasi secara lokal dalam mode pengembangan, Laravel telah menyediakan skrip _concurrent_ praktis. Cukup jalankan perintah berikut di dalam terminal Anda:

```bash
npm run serve
```

_(Perintah ini akan secara otomatis menjalankan peladen backend `php artisan serve` dan proses kompilasi frontend `npm run dev` secara bersamaan)_

Sebagai alternatif, Anda juga bisa menjalankannya di dua jendela terminal secara terpisah:

```bash
# Terminal 1 (Backend Laravel)
php artisan serve

# Terminal 2 (Kompilasi Frontend Vite/Vue)
npm run dev
```

Buka peramban (_browser_) Anda dan akses aplikasi di: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

## 🧪 Pengujian Otomatis (Testing)

Aplikasi ini menggunakan Pest untuk pengujian struktural. Untuk menjalankan seluruh rangkaian _unit test_ dan _feature test_, jalankan perintah berikut:

```bash
npm run test
# -- Atau --
php artisan test
```

Untuk memeriksa standar pengodean (_code style_) menggunakan Laravel Pint:

```bash
npm run lint:check
# Jika ingin memperbaiki format otomatis:
npm run lint
```

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
