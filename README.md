# Product Inventory with CSV Import

Aplikasi web sederhana berbasis **PHP Native** untuk mengelola data produk. Pengguna dapat menambahkan produk secara manual maupun mengimpor banyak produk melalui file CSV. Sebelum data diimpor ke database, sistem akan menampilkan pratinjau isi CSV sehingga pengguna dapat memastikan data yang akan disimpan sudah benar.

---

## 📌 Tujuan Project

Project ini dibuat sebagai latihan pengembangan aplikasi CRUD menggunakan PHP Native dengan tambahan fitur import data melalui file CSV. Fokus utama proyek adalah menunjukkan implementasi:

* CRUD menggunakan PHP Native
* Koneksi database menggunakan PDO
* Upload file menggunakan `multipart/form-data`
* Parsing file CSV menggunakan `fgetcsv()`
* Validasi data sebelum disimpan
* Operasi SQL (*INSERT*, *SELECT*, *UPDATE*, *DELETE*)
* Menampilkan data menggunakan tabel HTML
* Organisasi kode sederhana menggunakan struktur folder yang rapi

---

## 📌 Fitur

* Tambah produk secara manual
* Import produk melalui file CSV
* Preview data CSV sebelum diimport
* Menampilkan seluruh data produk dalam tabel
* Edit data produk
* Hapus data produk
* Pencarian produk
* Notifikasi proses berhasil atau gagal
* Antarmuka responsif menggunakan Bootstrap 5

---

## 🛠️ Tech Stack

### Frontend

* HTML5
* CSS3
* Bootstrap 5 (CDN)
* Vanilla JavaScript

### Backend

* PHP Native 8.x
* PDO (*PHP Data Objects*)

### Database

* MySQL / MariaDB

### Web Server

* XAMPP

---

## 📁 Struktur Folder

```text
product-inventory/

│
├── assets/
│   ├── css/
│   │     style.css
│   └── img/
│
├── config/
│     database.php
│
├── uploads/
│
├── includes/
│     header.php
│     footer.php
│
├── index.php
├── products.php
├── edit.php
├── delete.php
├── save.php
├── preview_import.php
├── import_process.php
└── sample.csv
```

---

## 🗄️ Database

### Tabel `products`

| Field        | Type                                |
| ------------ | ----------------------------------- |
| id           | INT AUTO_INCREMENT PRIMARY KEY      |
| product_name | VARCHAR(100)                        |
| category     | VARCHAR(50)                         |
| price        | DECIMAL(10,2)                       |
| stock        | INT                                 |
| supplier     | VARCHAR(100)                        |
| created_at   | TIMESTAMP DEFAULT CURRENT_TIMESTAMP |

---

## 📄 Halaman Aplikasi

### 1. Dashboard / Tambah Produk

Halaman utama yang menyediakan dua metode input data.

#### Tambah Produk Manual

Field yang tersedia:

* Nama Produk
* Kategori
* Harga
* Stok
* Supplier

Tombol:

* **Simpan Produk**

---

#### Import Produk CSV

Pengguna dapat memilih file CSV kemudian melakukan proses pratinjau sebelum data disimpan.

Tombol:

* **Choose File**
* **Preview CSV**

Navigasi:

* **Lihat Semua Produk**

---

### 2. Preview CSV

Setelah pengguna memilih file CSV dan menekan tombol **Preview CSV**, sistem akan:

* Memvalidasi file
* Membaca isi file menggunakan `fgetcsv()`
* Menampilkan seluruh isi CSV dalam bentuk tabel
* Menampilkan jumlah data yang akan diimport

Contoh tampilan:

| No | Nama Produk       | Kategori   | Harga  | Stok | Supplier |
| -- | ----------------- | ---------- | ------ | ---- | -------- |
| 1  | Keyboard Logitech | Elektronik | 250000 | 15   | Logitech |
| 2  | Mouse Gaming      | Elektronik | 150000 | 40   | Rexus    |
| 3  | Speaker JBL       | Audio      | 600000 | 5    | JBL      |

Di bagian bawah tersedia tombol:

* **Import ke Database**
* **Batal**

Apabila proses import berhasil, sistem akan menampilkan notifikasi seperti:

```
✓ 3 produk berhasil diimport.
```

Kemudian pengguna akan diarahkan ke halaman **Daftar Produk**.

---

### 3. Daftar Produk

Halaman ini menampilkan seluruh data produk dalam bentuk tabel.

Fitur:

* Menampilkan semua data
* Pencarian berdasarkan nama produk atau kategori
* Tombol Edit
* Tombol Delete

Contoh struktur tabel:

| ID | Nama Produk | Kategori   | Harga  | Stok | Supplier | Aksi          |
| -- | ----------- | ---------- | ------ | ---- | -------- | ------------- |
| 1  | Keyboard    | Elektronik | 250000 | 15   | Logitech | Edit • Delete |
| 2  | Mouse       | Elektronik | 150000 | 40   | Rexus    | Edit • Delete |

---

### 4. Edit Produk

Halaman untuk memperbarui data produk.

Field yang dapat diubah:

* Nama Produk
* Kategori
* Harga
* Stok
* Supplier

Tombol:

* **Update**

---

### 5. Delete Produk

Penghapusan data dilakukan melalui tombol **Delete** pada halaman daftar produk.

Sebelum data dihapus, sistem akan menampilkan konfirmasi menggunakan Bootstrap Modal.

Contoh:

```
Apakah Anda yakin ingin menghapus produk ini?

[Ya]
[Tidak]
```

---

## 🔄 Alur Sistem

```text
                 Dashboard

                      │

        ┌─────────────┴─────────────┐

        │                           │

 Tambah Manual                 Import CSV

        │                           │

        │                    Upload File

        │                           │

        │                     Preview CSV

        │                           │

        │                  Import ke Database

        └─────────────┬─────────────┘

                      │

               Data Produk

                      │

          ┌───────────┴───────────┐

          │                       │

        Edit                   Delete
```

---

## 📄 Contoh Format CSV

```csv
product_name,category,price,stock,supplier
Keyboard Logitech,Elektronik,250000,15,Logitech
Mouse Gaming,Elektronik,150000,40,Rexus
Speaker JBL,Audio,600000,5,JBL
Headset HyperX,Audio,950000,8,HyperX
Flashdisk Sandisk,Penyimpanan,120000,25,Sandisk
```

---

## ⚙️ Pembagian Proses Backend

### `index.php`

* Menampilkan form tambah produk
* Menampilkan form upload CSV

---

### `save.php`

* Memproses penyimpanan data produk secara manual ke database

---

### `preview_import.php`

Bertugas untuk:

* Memvalidasi file CSV
* Membaca data menggunakan `fgetcsv()`
* Menampilkan preview data
* Menyimpan hasil parsing sementara menggunakan *session* atau *hidden input*

---

### `import_process.php`

Bertugas untuk:

* Mengambil data hasil preview
* Menyimpan seluruh data ke tabel `products`
* Menampilkan notifikasi hasil import

---

### `products.php`

Bertugas untuk:

* Menampilkan seluruh data produk
* Menyediakan fitur pencarian sederhana menggunakan parameter `GET`

---

### `edit.php`

Bertugas untuk:

* Mengambil data berdasarkan ID
* Menampilkan form edit
* Menyimpan perubahan ke database

---

### `delete.php`

Bertugas untuk:

* Menghapus data produk berdasarkan ID
* Mengembalikan pengguna ke halaman daftar produk setelah proses selesai

---

## ▶️ Cara Menjalankan Project

1. Install XAMPP.
2. Jalankan **Apache** dan **MySQL**.
3. Clone atau salin project ke folder:

```text
htdocs/simple-product-manager
```

4. Buat database baru dengan nama:

```text
inventory_db
```

5. Import file SQL yang disediakan.

6. Sesuaikan konfigurasi database pada:

```text
config/database.php
```

7. Buka browser dan akses:

```text
http://localhost/simple-product-manager

```

---
