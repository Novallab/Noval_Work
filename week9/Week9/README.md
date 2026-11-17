# WEEK 9 — SPRINT 07: LARAVEL + CRUD + DATABASE (STOKKOS.ID)

Proyek ini merupakan implementasi materi **Web Programming Pertemuan 9: "Laravel + CRUD + Database"** (Sprint 07) untuk platform **StokKos.id**.

---

## 🚀 2 Cara Menjalankan Aplikasi

### Cara 1: Buka Langsung di Google Chrome (Instant Interactive Runner)
1. Buka folder: `C:\Users\ASUS VIVOBOOK\Downloads\Web\Week9`.
2. Klik ganda file **`index.html`** untuk langsung menjalankannya di Google Chrome.
3. Anda dapat langsung mendemonstrasikan:
   - **Tabel Database Realtime** (Data dibaca dinamis).
   - **Create:** Tombol *+ Tambah Barang* yang langsung menyuntikkan data baru.
   - **Read:** Menampilkan daftar record dari tabel `barangs`.
   - **Update:** Mengedit nama, kategori, stok, atau deskripsi barang.
   - **Delete:** Menghapus record barang dari database.
   - **Eloquent & SQL Query Inspector:** Menampilkan query PHP Eloquent dan SQL yang dieksekusi secara otomatis saat tombol diklik.

### Cara 2: Menjalankan via Terminal Laravel (Artisan)
Jika penguji meminta menjalankan via terminal Laravel:
```powershell
# 1. Buka terminal di folder proyek
cd "C:\Users\ASUS VIVOBOOK\Downloads\Web\Week9\stokkos-laravel"

# 2. Jalankan migration database (Task 01)
php artisan migrate

# 3. Jalankan server lokal Laravel
php artisan serve
```
Buka browser pada URL: `http://127.0.0.1:8000/barangs`

---

## 🎯 Pembahasan Materi Sprint 07 (Sesuai Slide 1 s/d 16)

### 1. Struktur Folder Laravel (Slide 2):
- `app/Models/Barang.php` & `Program.php` : Model Eloquent untuk berinteraksi dengan database.
- `database/migrations/` : File migration pembentuk tabel `barangs` dan `programs`.
- `app/Http/Controllers/BarangController.php` : Mengontrol logika CRUD.
- `resources/views/barangs/` : File tampilan menggunakan Blade templating engine (`index.blade.php`, `create.blade.php`, `edit.blade.php`).
- `routes/web.php` : File routing yang menghubungkan URL dengan Controller & Model.

### 2. Task 01: Create Database Structure (Migration - Slide 10):
```php
Schema::create('barangs', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('kategori');
    $table->integer('stok');
    $table->string('satuan');
    $table->text('deskripsi');
    $table->timestamps(); // created_at & updated_at
});
```

### 3. Task 02: Build Model (Eloquent ORM - Slide 11):
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model {
    protected $fillable = ['nama', 'kategori', 'stok', 'satuan', 'deskripsi'];
}
```

### 4. Task 03: Build CRUD Operations (Slide 12 & 13):
- **Create**: `Barang::create([...])`
- **Read**: `Barang::all()` dan `Barang::find($id)`
- **Update**: `$barang->update([...])`
- **Delete**: `Barang::destroy($id)` atau `$barang->delete()`

---

## 🎤 Jawaban Pertanyaan Refleksi & Ujian Demo (Slide 14 PPT)

### 1. Apa fungsi Migration?
> **Jawaban:** "Migration berfungsi sebagai *version control* untuk database. Migration memungkinkan kita mendefinisikan dan mengelola struktur tabel database melalui kode PHP terstruktur, sehingga skema tabel dapat dibuat atau diubah secara otomatis tanpa perlu mengutak-atik phpMyAdmin secara manual."

### 2. Apa fungsi Model?
> **Jawaban:** "Model berfungsi merepresentasikan entitas data dan struktur tabel database ke dalam bentuk kelas objek PHP, sehingga kita dapat berinteraksi dengan data menggunakan kaidah pemrograman berorientasi objek."

### 3. Apa fungsi Eloquent?
> **Jawaban:** "Eloquent adalah fasilitas **ORM (Object-Relational Mapping)** bawaan Laravel yang memudahkan manipulasi data database menggunakan sintaks objek PHP yang elegan, tanpa perlu menulis sintaks SQL manual (`SELECT`, `INSERT`, `UPDATE`, `DELETE`)."

### 4. Apa perbedaan `Barang::all()` dan `Barang::find(1)`?
> **Jawaban:** 
> - `Barang::all()` mengambil **seluruh baris data** dari tabel dan mengembalikannya dalam bentuk kumpulan banyak objek (*Collection*).
> - `Barang::find(1)` hanya mengambil **satu baris data spesifik** berdasarkan *primary key* `id = 1` dan mengembalikannya sebagai satu objek tunggal."

### 5. Apa yang dimaksud CRUD?
> **Jawaban:** "CRUD adalah empat fungsi dasar manipulasi data pada sistem informasi:
> - **C**reate: Menambahkan data baru ke dalam database.
> - **R**ead: Membaca dan menampilkan data yang tersimpan di database.
> - **U**pdate: Mengubah data yang sudah ada di database.
> - **D**elete: Menghapus data dari database."

### 6. Pertanyaan Utama: Apa yang berubah ketika aplikasi mulai menggunakan database?
> **Jawaban:** "Perubahan mendasarnya adalah **data tidak lagi ditulis mati (hard-coded)** di dalam file HTML atau JavaScript. Data kini tersimpan secara terpusat dan persisten di dalam sistem basis data (*database*), kemudian dibaca oleh framework Laravel melalui Eloquent Model, dan dirender secara dinamis ke tampilan peramban. Jika server dimuat ulang atau pengguna lain mengakses web, data tetap aman, mutakhir, dan konsisten."
