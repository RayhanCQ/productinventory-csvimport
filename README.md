# Product Inventory with CSV Import

Aplikasi web sederhana berbasis PHP Native untuk mengelola data produk. Produk dapat ditambahkan secara manual atau diimpor melalui CSV dengan tahap preview sebelum disimpan ke database.

## Fitur

- Tambah produk secara manual
- Import produk dari file CSV
- Validasi header dan isi CSV sebelum import
- Preview data CSV sebelum disimpan
- Menampilkan dan mencari produk berdasarkan nama atau kategori
- Edit dan hapus produk
- Pesan sukses dan error berbasis session
- Antarmuka responsif menggunakan Bootstrap 5

## Tech stack

- PHP Native 8.x
- PDO
- MySQL/MariaDB
- HTML5, CSS3, dan Vanilla JavaScript
- Bootstrap 5 melalui CDN
- XAMPP sebagai web server lokal

## Struktur folder dan file

```text
productinventory-csvimport/
├── assets/
│   └── css/
│       └── style.css              # Tema dan styling tambahan aplikasi
├── config/
│   └── database.php                # Koneksi PDO ke database
├── includes/
│   ├── footer.php                  # Footer, Bootstrap JavaScript, dan penutup HTML
│   ├── header.php                  # Session, navbar, Bootstrap CSS, dan pembuka HTML
│   └── product_validation.php      # Fungsi validasi harga dan data produk
├── tests/
│   └── product_validation_test.php # Pengujian sederhana fungsi validasi produk
├── uploads/                        # Penyimpanan sementara file CSV saat preview
├── delete.php                      # Menghapus produk berdasarkan ID
├── edit.php                        # Menampilkan dan memproses form edit produk
├── import_process.php              # Menyimpan hasil preview CSV ke database
├── index.php                       # Dashboard, form produk manual, dan upload CSV
├── preview_import.php              # Upload, validasi, dan preview file CSV
├── products.php                    # Daftar produk dan pencarian
├── save.php                         # Menyimpan produk baru ke database
├── sample.csv                       # Contoh format data CSV
└── README.md                        # Dokumentasi project
```

Folder `uploads/` digunakan sementara ketika file CSV diproses dan perlu tersedia serta dapat ditulisi oleh web server. File CSV sementara dihapus setelah selesai dibaca.

## Detail file utama

### `index.php`

Menampilkan dashboard dengan form tambah produk manual, form upload CSV, format header CSV yang diharapkan, serta tautan ke daftar produk.

### `save.php`

Memvalidasi input produk manual menggunakan `includes/product_validation.php`, lalu menyimpannya ke tabel `products` menggunakan prepared statement PDO.

### `preview_import.php`

Memastikan request dan upload valid, memeriksa ekstensi serta header CSV, membaca setiap baris dengan `fgetcsv()`, memvalidasi data produk, lalu menyimpan hasil yang valid ke session untuk ditampilkan sebagai preview.

### `import_process.php`

Mengambil produk dari session setelah pengguna menyetujui preview dan memasukkan seluruh data ke tabel `products` dalam transaksi database.

### `products.php`

Mengambil daftar produk, mendukung pencarian berdasarkan nama atau kategori, dan menyediakan tombol edit serta delete.

### `edit.php`

Mengambil produk berdasarkan ID, menampilkan form edit, memvalidasi perubahan, dan memperbarui data di database.

### `delete.php`

Memvalidasi ID dari request POST, memastikan produk tersedia, lalu menghapusnya dari database.

### `includes/product_validation.php`

Berisi fungsi bersama untuk memvalidasi harga maksimal `99999999.99`, stok bilangan bulat tidak negatif, serta field produk yang wajib diisi. Fungsi ini digunakan oleh proses tambah, edit, dan import CSV.

### `tests/product_validation_test.php`

Berisi assertion sederhana untuk memeriksa data valid, harga negatif atau berformat tidak valid, dan stok negatif.

## Format CSV

Header CSV harus sama persis dengan format berikut:

```csv
product_name,category,price,stock,supplier
Keyboard Logitech,Elektronik,250000,15,Logitech
Mouse Gaming,Elektronik,150000,40,Rexus
Speaker JBL,Audio,600000,5,JBL
```

Harga menggunakan angka dengan maksimal dua angka desimal. Stok harus berupa bilangan bulat nol atau lebih.

## Database

Buat database bernama `inventory_db`, lalu buat tabel `products` dengan struktur berikut:

| Field | Type |
| --- | --- |
| `id` | `INT AUTO_INCREMENT PRIMARY KEY` |
| `product_name` | `VARCHAR(100)` |
| `category` | `VARCHAR(50)` |
| `price` | `DECIMAL(10,2)` |
| `stock` | `INT` |
| `supplier` | `VARCHAR(100)` |
| `created_at` | `TIMESTAMP DEFAULT CURRENT_TIMESTAMP` |

Konfigurasi default koneksi ada di `config/database.php`:

```php
$host = 'localhost';
$dbname = 'inventory_db';
$username = 'root';
$password = '';
```

Sesuaikan nilainya jika konfigurasi MySQL lokal berbeda.

## Menjalankan project

1. Install dan jalankan Apache serta MySQL melalui XAMPP.
2. Letakkan project di dalam folder `htdocs`, misalnya:

   ```text
   htdocs/productinventory-csvimport
   ```

3. Buat database dan tabel `products` sesuai bagian **Database**.
4. Sesuaikan koneksi di `config/database.php` jika diperlukan.
5. Buka aplikasi melalui:

   ```text
   http://localhost/productinventory-csvimport/
   ```

## Menjalankan test

Dari root project, jalankan:

```bash
php tests/product_validation_test.php
```

Jika tidak ada output atau error, assertion validasi berhasil dijalankan.
