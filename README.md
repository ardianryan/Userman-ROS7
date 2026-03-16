# Userman-ROS7

Sebuah aplikasi berbasis web (PHP) untuk mengelola **User Manager pada MikroTik RouterOS versi 7 (ROS7)** menggunakan integrasi RouterOS API. Aplikasi ini dirancang dengan antarmuka yang modern, bersih, dan responsif, memudahkan administrator maupun pelanggan jaringan untuk berinteraksi dengan layanan Hotspot atau Radius Radius Server MikroTik.

![MikroTik Userman ROS7](public/assets/img/mangoteklogo.png)

## 📌 Fitur Utama

Aplikasi ini dibagi menjadi dua bagian: **Admin Panel** dan **User / Portal Panel**.

### Admin Panel
*   **Dashboard Utama:** Ringkasan statistik pengguna, router yang aktif, status lisensi, dll.
*   **Manajemen Router:** Menambah, melihat, mengedit, dan menghapus koneksi ke sistem router MikroTik menggunakan API User dan Secret.
*   **Radius / Userman Users:** Kelola akun pelanggan (Tambah, Edit, Hapus User Radius).
*   **Manajemen Profiles:** Membuat profil kecepatan dan kuota waktu / data untuk pelanggan Hotspot/PPPoE.
*   **Profile Limitations:** Menentukan limitasi kecepatan jaringan berserta konfigurasinya langsung dari UI web.
*   **Sessions & Payments (Upcoming/In-development):** Melacak sesi aktif dari pengguna dan memantau riwayat pembayaran/voucher.
*   **Settings:** Pengaturan dasar sistem admin, merubah atribut default radius, dll.

### Portal / User Panel
*   **Registrasi & Login Akun Baru:** Pelanggan baru bisa mendaftarkan akun di jaringan RT/RW Net Anda melalui halaman Pendaftaran dan login.
*   **User Dashboard:** Pelanggan dapat melihat profil langganan saat ini, sisa kuota data / waktu, akun aktif, status layanan.
*   **Change Password:** Fitur mengganti kata sandi akun portal mandiri.

## 🛠️ Tech Stack & Library

*   **Backend:** PHP (Native/MVC Pattern)
*   **Database:** MySQL / MariaDB (Database: `mikrotik_userman`)
*   **API:** [RouterOS API PHP](https://github.com/BenMenking/routeros-api) (Koneksi ke sistem Mikrotik versi RouterOS 7)
*   **Frontend:** HTML5, CSS3, Vanilla JS
*   **CSS Framework:** Bootstrap 5
*   **Icon:** FontAwesome 6 (Lokal - Tidak memerlukan koneksi internet tambahan untuk memuat icon)
*   **Arsitektur MVC:** Sederhana, mudah dipahami (Controller, Model, View + Router PHP Dasar)

## 🚀 Cara Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi pada *local server* (XAMPP/MAMP/Laragon) maupun Server Hosting Anda.

1.  **Clone Repository:**
    ```bash
    git clone https://github.com/ardianryan/Userman-ROS7.git
    cd Userman-ROS7
    ```

2.  **Persiapkan Database:**
    *   Buka phpMyAdmin atau *client database* pilihan Anda (DBeaver, TablePlus, dll).
    *   Buat database baru dengan nama `mikrotik_userman` (atau bebas).
    *   Impor file `setup.sql` yang ada di dalam root proyek ini ke database tersebut.
    *   Secara *default*, file SQL ini akan memasukkan akun Admin yaitu `admin` dengan password `admin123`.

3.  **Konfigurasi Database & Base URL:**
    *   Buka file `app/config/config.php` (Jika Anda menggunakan database default, mungkin tidak perlu mengubah):
        ```php
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root'); // Sesuaikan dengan user database Anda (Default XAMPP: root)
        define('DB_PASS', ''); // Kosongkan jika XAMPP, atau isi jika ada password
        define('DB_NAME', 'mikrotik_userman');
        ```

4.  **Menjalankan Proyek:**
    *   Pastikan *document root* web server diarahkan ke direktori proyek utama (atau `/public` direktori).
    *   Gunakan PHP Built-in Server untuk testing (Jalankan terminal di direktori proyek):
        ```bash
        php -S localhost:8000 -t public
        ```
    *   Buka browser dan akses ke `http://localhost:8000`.

## 🔑 Akses Login Default Admin

Setelah Anda mengimpor database, Anda dapat login ke halaman sistem administrator menggunakan informasi berikut:

*   **URL Portal:** `http://localhost:8000/public/`
*   **Role:** Admin
*   **Username:** `admin`
*   **Password:** `admin123`

Untuk mengakses *User Portal/Dashboard*, Anda/pelanggan dapat menuju menu **Register** secara mandiri pada halaman login portal.

## 📁 Struktur Direktori Utama

*   `app/config/` - Konfigurasi base URL, informasi akses Database.
*   `app/controllers/` - Mengelola rute logika dari Frontend untuk Portal, Auth, User, Admin.
*   `app/core/` - Sistem Inti (MVC Router, `Database.php`, `Controller.php`) serta Koneksi MikroTik API (`RouterosAPI.php`).
*   `app/models/` - Mengelola Query DB terkait Router, Profil, Pengaturan Admin, dll.
*   `app/views/` - File Tampilan UI (Blade-like rendering via PHP Require).
*   `public/` - File publik seperti `index.php` (Entry point), CSS, JS, Asset Font Awesome, dan Logo.

## 🤝 Kontribusi

Selamat bereksperimen! Jika Anda merasa ini berguna atau ingin memperbaiki / menambahkan sesuatu (Mungkin perbaikan bug atau tambahan UI), silahkan beri *Pull Request* atau sampaikan di bagian *Issues*.

---
*Dibuat untuk Manajemen Hotspot & Radius Mikrotik yang lebih modern dan mudah dikelola.*