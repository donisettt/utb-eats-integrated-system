# UTB Eats - Digital Canteen Solution 🍔📱

<p align="left">
  <img src="docs/banner_web.png" width="300">
  <img src="docs/banner_student.png" width="300">
</p>

<p align="left">
  <img src="docs/banner_student2.png" width="300">
  <img src="docs/banner_merchant.png" width="300">
</p>

---

**UTB Eats** adalah platform ekosistem digital kantin Universitas Teknologi Bandung yang terintegrasi. Sistem ini menghubungkan Mahasiswa, Mitra Kantin (Merchant), dan Pihak Kampus (Admin) dalam satu aplikasi untuk efisiensi pemesanan, pengelolaan stok, dan pelaporan keuangan.

Proyek ini dikembangkan untuk memenuhi Tugas (UTS) Mata Kuliah Pemrograman Mobile 2.

---

## 🌟 Fitur Utama (Key Features)

Sistem ini terdiri dari **3 Role** utama dengan fitur spesifik:

### 1. Mobile App (Mahasiswa/User)
* **Autentikasi:** Login & Register dengan validasi data.
* **Smart Dashboard:** Banner promo *auto-scroll*, kategori menu, dan rekomendasi warung terlaris.
* **AI Food Assistant (Gemini):** Fitur chat cerdas menggunakan Google Gemini untuk rekomendasi makanan berdasarkan cuaca atau mood.
* **Pencarian & Filter:** Cari makanan atau warung secara real-time.
* **Detail Warung Premium:** Tampilan *Parallax Header* dengan daftar menu yang elegan.
* **Keranjang Belanja:** Kelola pesanan, tambah catatan, dan hitung total harga otomatis.
* **Checkout & Tracking:** Pemesanan dengan alamat pengantaran dan pelacakan status order (Pending -> Proses -> Selesai).
* **Profil Pengguna:** Manajemen profil dengan foto (upload ke server) dan riwayat transaksi.

### 2. Mobile App (Merchant/Mitra)
* **Dashboard Order:** Manajemen pesanan masuk dengan tabulasi status (Baru, Proses, Selesai).
* **Manajemen Menu (CRUD):** Tambah, Edit, Hapus produk lengkap dengan upload foto dan status ketersediaan.
* **Pengaturan Toko:** Ubah nama toko, jam operasional, dan logo toko secara mandiri.
* **Laporan Keuangan:** Melihat total pendapatan dan riwayat transaksi sukses secara *real-time*.
* **Notifikasi Pesanan:** Mendapatkan update langsung saat ada pesanan baru (via Firebase/Refresh).

### 3. Web Admin Panel (Kampus)
* **Dashboard Statistik:** Ringkasan total mitra, pengguna, dan omzet harian.
* **Manajemen Mitra:** Daftarkan mitra baru, pantau status toko, dan kelola data.
* **Manajemen Pengguna:** Kelola data mahasiswa, dosen, dan staf.
* **Manajemen Iuran:** Fitur keuangan untuk menagih iuran bulanan mitra & cetak laporan.
* **Manajemen Banner:** Upload banner promo yang langsung tampil di aplikasi mobile.
* **Pusat Laporan:** Cetak laporan transaksi penjualan dalam format PDF siap print.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

### Frontend (Mobile App)
* **Framework:** Flutter (SDK 3.35.5)
* **State Management:** Provider
* **Networking:** Dio
* **UI Library:** Google Fonts, Flutter Animate, Shimmer
* **Features:** Image Picker, Shared Preferences, Url Launcher
* **AI Integration:** Google Generative AI SDK (Gemini)

### Backend (Web & API)
* **Framework:** Laravel 12
* **Database:** MySQL
* **Authentication:** Laravel Sanctum (API Token)
* **Storage:** Local Storage (Public Disk)
* **Styling:** Bootstrap 5 (Admin Panel)

### Services & Tools
* **Ngrok:** Untuk tunneling local server ke internet (Testing di HP fisik).
* **Firebase Realtime Database:** Untuk trigger notifikasi pesanan real-time.

## 👤 Tentang Pengembang

Project ini dibuat dengan ❤️ oleh:

**Doni Setiawan Wahyono**
* **Role:** Mobile Application Developer
* **Kampus:** Universitas Teknologi Bandung
* **NPM:** 23552011146

[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue.svg)](https://www.linkedin.com/in/doni-setiawan-wahyono)
[![GitHub](https://img.shields.io/badge/GitHub-Follow-black.svg)](https://www.github.com/donisettt)
[![Instagram](https://img.shields.io/badge/Instagram-Follow-purple.svg)](https://www.instagram.com/dnisetyaw)