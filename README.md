# 🌴 Website BPPD Sulawesi Utara

**Website Resmi Badan Promosi Pariwisata Daerah Sulawesi Utara (BPPD SULUT)**  
Platform digital yang dirancang untuk mempromosikan potensi pariwisata, kuliner, dan event budaya Sulawesi Utara secara luas dan terstruktur.

---

## 📌 Deskripsi Proyek

Website ini dibangun menggunakan Laravel sebagai backend dan Tailwind CSS untuk frontend.  
Tujuannya adalah menjadi pusat informasi yang interaktif dan informatif bagi wisatawan dan pelaku industri pariwisata di Sulawesi Utara.

---

## 🚀 Fitur Utama

-   🧑‍💼 Multi-role Login (Admin, Pengusaha Kuliner, Pengusaha Wisata, Seniman, Pengguna)
-   🗺️ CRUD Tempat Wisata dan Tempat Kuliner oleh pemilik masing-masing
-   📷 Upload Gambar, Jam Operasional, dan Review Pengguna
-   🎭 Sistem Event yang memungkinkan Seniman untuk berpartisipasi
-   📊 Dashboard Admin untuk kelola data promosi pariwisata

---

## ⚙️ Teknologi

-   Laravel 12
-   PHP 8.3
-   Alpine JS
-   Tailwind CSS
-   MySQL

---

## 🛠️ Instalasi & Setup

1. Clone repository:

    ```bash
    git clone https://github.com/TheoWongkar/bppd-sulut.git
    cd bppd-sulut
    ```

2. Install dependency:

    ```bash
    composer install
    npm install && npm run build
    ```

3. Salin file `.env`:

    ```bash
    cp .env.example .env
    ```

4. Atur konfigurasi `.env` (database, mail, dsb)

5. Generate key dan migrasi database:

    ```bash
    php artisan key:generate
    php artisan storage:link
    php artisan migrate:fresh --seed
    ```

6. Jalankan server lokal:

    ```bash
    php artisan serve
    ```

7. Buka browser dan akses http://bppd-sulut.com

---

## 👥 Role & Akses

| Role              | Akses                                   |
| ----------------- | --------------------------------------- |
| Admin             | Kelola semua data, termasuk event       |
| Pengusaha Kuliner | CRUD data kuliner miliknya sendiri      |
| Pengusaha Wisata  | CRUD data wisata miliknya sendiri       |
| Seniman           | CRUD partisipasi event miliknya sendiri |
| Pengguna          | Memberikan review & melihat konten      |

---

## 📎 Catatan Tambahan

-   Pastikan folder `storage` dan `bootstrap/cache` writable.
-   Project ini bisa diperluas untuk promosi UMKM dan integrasi dengan media sosial.

---
