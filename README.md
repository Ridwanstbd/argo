# Sistem Kasir Argo Blastcoating

Sistem Kasir Argo Blastcoating adalah aplikasi manajemen transaksi dan pembayaran berbasis web yang dikembangkan dengan PHP native untuk perusahaan jasa blastcoating. Aplikasi ini dirancang untuk memudahkan pencatatan transaksi, pengelolaan layanan, dan pengelolaan Barang

## Fitur Utama

- Manajemen transaksi jasa blastcoating
- Pencatatan data pelanggan
- Pengelolaan layanan dan barang yang dikerjakan
- Interface yang sederhana dan mudah digunakan

## Persyaratan Sistem

- PHP versi 7.0 atau lebih tinggi
- MySQL/MariaDB
- Web Server (Apache/XAMPP/WAMP)
- Web Browser (Chrome/Firefox/Safari)

## Cara Instalasi

1. Clone repository ini

```bash
git clone https://github.com/Ridwanstbd/kasir-argo-blastcoating.git
```

2. Pindahkan folder project ke direktori web server Anda:

   - Untuk XAMPP: pindahkan ke `htdocs/`
   - Untuk WAMP: pindahkan ke `www/`

3. Buat database baru di MySQL/phpMyAdmin

4. Import file SQL

```bash
argo-blastcoating.sql
```

5. Konfigurasi koneksi database

- Buka file `controller/koneksi.php`
- Sesuaikan pengaturan berikut:

```php
$conn = mysqli_connect("localhost", "root", "", "argo-blastcoating");
```

6. Akses aplikasi melalui web browser

```
http://localhost/kasir-argo-blastcoating
```

## Penggunaan

1. Login ke sistem menggunakan kredensial default:

   - Username: test
   - Password: 12345678

2. Fitur yang tersedia:
   - Pesanan : edit, hapus pesanan
   - Manajemen Klien: Tambah, edit, hapus data pelanggan
   - Manajemen Barang : Tambah, edit, hapus data Barang

## Struktur Direktori

```
kasir-argo-blastcoating/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── controllers/
│   ├── db.php
│   ├── csrf_handler.php
│   └── barangController.php
│   └── loginController.php
│   └── klienController.php
│   └── registerController.php
│   └── pesananController.php
├── index.php
├── barang.php
├── buat-pesanan.php
├── get_order_details.php
├── index.php
├── klien.php
├── login.php
├── logout.php
├── pesanan.php
├── process_order.php
├── register.php
└── README.md
```

## Kontribusi

Jika Anda menemukan bug atau ingin menambahkan fitur baru, silakan buat Issue atau Pull Request di repository ini.

## Lisensi

[MIT License](LICENSE)

## Dukungan

Untuk pertanyaan dan bantuan, silakan hubungi:

- Email: ridwansetiobudi77@gmail.com
- WhatsApp: 085704412510
