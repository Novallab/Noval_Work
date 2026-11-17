# WEEK 10 — SPRINT 08: CONTROLLER, ROUTING & VALIDATION (STOKKOS.ID)

Lanjutan Week 9 (Migration → Model → Eloquent → CRUD). Objek data tetap **Barang** (di slide disebut *Program*).

## Cara Menjalankan

**1. Demo cepat (tanpa Laravel):** buka `index.html` di Chrome. Berisi CRUD + validasi, API Tester ala Postman, dan visual alur request.

**2. Laravel:** salin isi `stokkos-laravel/` ke project Laravel (11/12) yang sama dengan Week 9, lalu:
```bash
php artisan install:api     # mengaktifkan routes/api.php (Laravel 11+)
php artisan migrate
php artisan serve
```
- Web: `http://127.0.0.1:8000/barangs`
- API: `http://127.0.0.1:8000/api/barangs` (uji dengan Postman/Insomnia, header `Accept: application/json`)

## Isi Sprint 08

| Task | Berkas |
|---|---|
| 01 Move CRUD into Controller | `app/Http/Controllers/BarangController.php`, `routes/web.php` |
| 02 Request & Validation | `Barang::rules()` / `Barang::messages()`, `$request->validate()`, `resources/views/barangs/_form.blade.php` (pesan error + `old()`) |
| 03 RESTful API | `app/Http/Controllers/Api/BarangController.php`, `routes/api.php` |

**Aturan validasi:** nama `required|string|max:100`, kategori `required|in:Makanan,Mandi,Kebersihan,P3K`, stok `required|integer|min:0`, satuan `required|string|max:20`, deskripsi `required|string`.

## Endpoint API

| Method | URL | Sukses | Gagal |
|---|---|---|---|
| GET | `/api/barangs` | 200 | |
| POST | `/api/barangs` | 201 | 422 |
| GET | `/api/barangs/{id}` | 200 | 404 |
| PUT/PATCH | `/api/barangs/{id}` | 200 | 404, 422 |
| DELETE | `/api/barangs/{id}` | 200 | 404 |

Contoh body POST:
```json
{ "nama": "Sabun Mandi", "kategori": "Mandi", "stok": 4, "satuan": "Pcs", "deskripsi": "Stok sabun bersama." }
```

## Tema Warna
Teal `#14b8a6` (merek / stok aman), amber (stok menipis), rose (stok habis / error), latar `#0d1b1a`. Warna dikonfigurasi di `tailwind.config` pada `layouts/app.blade.php` dan `index.html`.

## Jawaban Review
1. **Controller:** menerima request, memproses (validasi, Eloquent), lalu menghasilkan response.
2. **Route:** menghubungkan URL + HTTP method ke controller dan method-nya.
3. **Yang diterima Controller:** objek `Request` berisi input pengguna (`$request->nama`).
4. **Validation:** memastikan data memenuhi aturan sebelum masuk database.
5. **HTML vs JSON:** HTML untuk browser (view), JSON untuk client frontend/mobile (API).
6. **HTTP method:** menentukan jenis operasi (GET=read, POST=create, PUT/PATCH=update, DELETE=delete).
7. **Status code:** 200 sukses, 201 dibuat, 404 tidak ditemukan, 422 gagal validasi.
8. **Alur:** User → Route → Controller → Validation → Model → Eloquent → Database → Response.
