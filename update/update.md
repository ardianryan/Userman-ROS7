# Guide: Creating Updates for Userman-ROS7

Petunjuk ini menjelaskan cara membuat paket pembaruan (update package) yang rapi untuk dikirim ke lingkungan produksi.

## 📁 Struktur Direktori Update

Setiap pembaruan harus diletakkan dalam sub-direktori berversi di dalam folder `update/`. Contoh:
`update/update-1.0.1/`

Struktur di dalam folder versi harus mengikuti struktur asli proyek:
- `app/` (core, models, views, controllers)
- `public/` (css, js, assets, .htaccess)

## 🛠️ Langkah-langkah Membuat Update

1. **Identifikasi File yang Diubah**:
   Catat semua file yang telah Anda modifikasi atau tambahkan selama pengembangan fitur baru.

2. **Siapkan Direktori**:
   Buat folder baru dengan nomor versi, misalnya: `update/update-1.0.2/`.

3. **Salin File**:
   Salin file yang diubah ke dalam folder versi tersebut dengan tetap menjaga struktur foldernya. 
   *Contoh:* Jika Anda mengubah `app/models/UserModel.php`, maka letakkan di `update/update-1.0.2/app/models/UserModel.php`.

4. **Sertakan Update SQL (Jika ada)**:
   Jika ada perubahan struktur database (tabel baru, kolom baru), buat file `update.sql` di dalam folder versi tersebut.

5. **Sertakan Dokumentasi Terbatas (Opsional)**:
   Jika ada perubahan besar, Anda bisa menyertakan file `README.md` yang telah diperbarui di dalam folder update.

6. **Kompresi (Zipping)**:
   Zipping seluruh folder `update/` sehingga struktur akhirnya saat di-extract adalah:
   ```
   update.zip
   └── update/
       ├── update.md (File ini)
       └── update-1.0.1/
           ├── app/
           ├── public/
           └── update.sql (File SQL)
   ```

## 🚀 Cara Menggunakan (Production)

1. **Upload & Extract**: Upload `update.zip` ke server dan extract.
2. **Database Update**: Jika terdapat file `update.sql` di dalam folder versi (misal: `update-1.0.1/update.sql`), jalankan file tersebut di database production Anda (via phpMyAdmin atau terminal).
3. **Copy Files**: Salin isi dari folder versi (misal: `update-1.0.1/*`) ke root direktori aplikasi Anda (kecuali file `.sql`).
4. **Overwrite**: Timpa (overwrite) file yang sudah ada.

---
