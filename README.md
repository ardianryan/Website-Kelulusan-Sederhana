# Portal Pengumuman Kelulusan SMAN 1 Sooko

Website portal pengumuman kelulusan siswa berbasis PHP yang modern, responsif, dan interaktif. Dilengkapi dengan sistem manajemen data admin, import Excel, dan efek transisi sinematik.

## ✨ Fitur Utama

- **Antarmuka Premium**: Desain modern dengan tema *glassmorphism* dan partikel latar belakang yang dinamis.
- **Transisi Sinematik**: Efek animasi teks *Star Wars style* saat pengumuman kelulusan dibuka untuk memberikan kesan dramatis.
- **Audio Feedback**: Umpan balik suara sintetis otomatis saat pengumuman (tanpa perlu file MP3 eksternal).
- **Sistem Installer**: Memudahkan konfigurasi database dan setup awal melalui antarmuka web.
- **Dashboard Admin**: Manajemen data siswa dengan fitur pencarian, pagination, dan urutan (*sorting*) otomatis (Kelas > Nama).
- **Import Excel (XLSX)**: Memasukkan data siswa dalam jumlah besar secara cepat dengan fitur *upsert* (update jika NISN sudah ada).
- **Keamanan .env**: Menggunakan variabel lingkungan untuk menyimpan kredensial database agar lebih aman.
- **Countdown Timer**: Sistem pembukaan pengumuman otomatis berdasarkan waktu yang diatur di database.

## 🚀 Instalasi

1. **Clone/Download** repositori ini ke folder server lokal Anda (XAMPP/Laragon).
2. Jalankan perintah berikut untuk menginstal dependensi:
   ```bash
   composer install
   ```
3. Akses aplikasi melalui browser (misal: `http://localhost/Kelulusan-SMAN1Sooko`).
4. Anda akan otomatis diarahkan ke halaman **Installer**.
5. Masukkan kredensial database Anda (Host, Nama DB, User, Password).
6. Selesai! Login ke admin menggunakan:
   - **Username**: `admin`
   - **Password**: `admin`

## 🛠️ Teknologi yang Digunakan

- **PHP 8.x** (Native/PDO)
- **MariaDB/MySQL**
- **Tailwind CSS** (via CDN untuk utilitas)
- **Vanilla Javascript** (ES6+)
- **Web Audio API** (untuk suara pengumuman)
- **Composer** (PhpSpreadsheet & PhpDotEnv)

## 📂 Struktur Folder

- `/data-kelulusan`: Folder inti backend (config, auth, dashboard, dll).
- `/sql`: Berisi skema database (`setup.sql`).
- `/uploads`: Tempat penyimpanan logo sekolah.
- `/assets`: Aset gambar dan screenshot.
- `index.php`: Router utama aplikasi.
- `home.php`: Halaman depan (portal siswa).

---
© 2026 Tim IT SMA Negeri 1 Sooko
