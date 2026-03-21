# AuraRadius

**AuraRadius** adalah dashboard manajemen RADIUS & User Manager (MikroTik RouterOS v7) generasi berikutnya yang dirancang untuk performa tinggi dan keamanan maksimal. Aplikasi ini memberikan kontrol penuh atas infrastruktur jaringan Anda, khususnya dioptimalkan untuk autentikasi **WPA2/WPA3 Enterprise (EAP/PEAP)** dan integrasi sistem **Hotspot Captive Portal**.

![AuraRadius Branding](public/assets/img/mangoteklogo.png)

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
*   **Performance & Caching (New):** Sistem caching cerdas berbasis file untuk mempercepat pemuatan data pengaturan dan mengurangi beban database.

### Portal / User Panel
*   **Registrasi & Login Akun Baru:** Pelanggan baru bisa mendaftarkan akun di jaringan RT/RW Net Anda melalui halaman Pendaftaran dan login.
*   **User Dashboard:** Pelanggan dapat melihat profil langganan saat ini, sisa kuota data / waktu, akun aktif, status layanan.
*   **Change Password:** Fitur mengganti kata sandi akun portal mandiri.
*   **Cookies Agreement (New):** Pop-up persetujuan cookies modern dengan gaya glassmorphism untuk kepatuhan privasi pengguna.

## 🛠️ Tech Stack & Library

*   **Backend:** PHP (Native/MVC Pattern)
*   **Database:** MySQL / MariaDB (Database: `mikrotik_userman`)
*   **API:** [RouterOS API PHP](https://github.com/BenMenking/routeros-api) (Koneksi ke sistem Mikrotik versi RouterOS 7)
*   **Frontend:** HTML5, CSS3, Vanilla JS
*   **CSS Framework:** Bootstrap 5
*   **Icon:** FontAwesome 6 (Lokal - Tidak memerlukan koneksi internet tambahan untuk memuat icon)
*   **Arsitektur MVC:** Sederhana, mudah dipahami (Controller, Model, View + Router PHP Dasar)
*   **Caching Engine:** Custom file-based caching untuk optimasi performa backend.
*   **Web Optimization:** Gzip compression dan Browser Caching melalui konfigurasi `.htaccess`.

## 📦 Dependensi Sistem

Untuk menjalankan aplikasi ini dengan lancar, pastikan server Anda memenuhi kriteria berikut:

| Dependensi | Versi Minimal | Keterangan |
| :--- | :--- | :--- |
| **PHP** | 7.4+ (Rekomendasi 8.1+) | Membutuhkan ekstensi `pdo_mysql`, `openssl`, `curl`. |
| **Database** | MariaDB 10.4+ / MySQL 5.7+ | Digunakan untuk menyimpan pengaturan aplikasi dan log admin. |
| **RouterOS** | v7.x (Stable) | Mendukung integrasi dengan User Manager v7. |
| **Web Server** | Apache / Nginx | Membutuhkan fitur `mod_rewrite` (Apache) untuk sistem routing. |
| **Browser** | Modern Browser | Support CSS Grid, Flexbox, dan modern Javascript. |

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

*   `app/cache/` - Penyimpanan file cache sementara untuk mempercepat performa aplikasi.
*   `app/config/` - Konfigurasi base URL, informasi akses Database.
*   `app/controllers/` - Mengelola rute logika dari Frontend untuk Portal, Auth, User, Admin.
*   `app/core/` - Sistem Inti (MVC Router, `Database.php`, `Controller.php`, `Cache.php`) serta Koneksi MikroTik API (`RouterosAPI.php`).
*   `app/models/` - Mengelola Query DB terkait Router, Profil, Pengaturan Admin, dll.
*   `app/views/` - File Tampilan UI (Blade-like rendering via PHP Require).
*   `public/` - File publik seperti `index.php` (Entry point), CSS, JS, Asset Font Awesome, dan Logo.

## 🤝 Kontribusi

Selamat bereksperimen! Jika Anda merasa ini berguna atau ingin memperbaiki / menambahkan sesuatu (Mungkin perbaikan bug atau tambahan UI), silahkan beri *Pull Request* atau sampaikan di bagian *Issues*.

---
*Dibuat untuk Manajemen Hotspot & Radius Mikrotik yang lebih modern dan mudah dikelola.*