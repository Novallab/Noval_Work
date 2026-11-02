# 📦 Stock Kos - Week 6 (Object-Oriented PHP)
> **Materi:** Pertemuan 6 Web Programming — *From Procedural to Object-Oriented (Sprint 05)*  
> **Repository GitHub:** [https://github.com/Novallab/Stock-Kos](https://github.com/Novallab/Stock-Kos)

---

## 🎯 Target Pembelajaran Sprint 05 yang Diimplementasikan
- [x] **Class PHP:** Membuat class blueprint `StockItem` dan `InventoryManager`.
- [x] **Object PHP:** Melakukan instansiasi object dengan keyword `new`.
- [x] **Property & Method:** Memisahkan data (property) dan perilaku (method) dalam class.
- [x] **Constructor:** Menggunakan `__construct()` untuk inisialisasi object saat dibuat.
- [x] **Encapsulation:** Property bersifat `private` dan diakses secara aman melalui `getters` dan `setters`.
- [x] **Object -> PHP -> HTML:** Menghasilkan komponen tampilan HTML secara dinamis langsung dari method object (`$item->toHtmlCard()`).
- [x] **Persistent Storage:** Data tersimpan dan diperbarui ke `data/stock.json` saat ada aksi tambah, ubah stok, atau hapus barang.

---

## 📂 Struktur Folder Week 6
```
Week 6/
├── classes/
│   ├── StockItem.php          # Class blueprint entitas barang (properties, getters, setters, HTML generator)
│   └── InventoryManager.php   # Class pengelola koleksi object (load/save JSON, filter, sort, statistics)
├── data/
│   └── stock.json             # Database penyimpanan inventaris kos (JSON format)
├── css/
│   └── output.css             # Tailwind CSS v4 styling
├── img/
│   └── img1.jpg               # Gambar banner inventaris
├── index.php                  # Halaman utama aplikasi (Object-Oriented UI & Action Handler)
├── run.bat                    # Script 1-klik untuk menjalankan server lokal
└── README.md                  # Dokumentasi panduan
```

---

## 🚀 Cara Menjalankan Aplikasi

### Cara 1: Menggunakan Script 1-Klik (`run.bat`) — Paling Mudah
1. Buka folder `Week 6`.
2. Klik ganda file **`run.bat`**.
3. Browser akan otomatis terbuka di `http://localhost:8000`.

---

### Cara 2: Menggunakan Terminal / Command Prompt
Buka terminal di dalam folder `Week 6`:
```bash
cd "Week 6"
"C:\xampp\php\php.exe" -S localhost:8000
```
Buka browser dan akses: **`http://localhost:8000`**

---

### Cara 3: Menggunakan XAMPP Apache
1. Salin atau buat shortcut folder `StockKos` ke dalam folder `C:\xampp\htdocs\`.
2. Nyalakan modul **Apache** di XAMPP Control Panel.
3. Buka browser dan akses:  
   `http://localhost/StockKos/Week%206/`

---

## 💡 Pembahasan Alur OOP (Slide 10 Modul)
1. **Class apa yang dibuat?**
   - `StockItem`: Merepresentasikan barang inventaris kos.
   - `InventoryManager`: Mengelola kumpulan object barang.
2. **Object apa yang digunakan?**
   - Object `$manager` untuk pengelolaan inventaris dan object `$item` untuk setiap kartu barang.
3. **Property apa yang dimiliki?**
   - Private properties: `$id, $name, $category, $stock, $minStock, $unit, $location, $icon`.
4. **Method apa yang dibuat?**
   - `__construct()`, `getId()`, `getName()`, `getStock()`, `getStatus()`, `isLowStock()`, `increaseStock()`, `decreaseStock()`, `toHtmlCard()`, `addItem()`, `deleteItem()`, `getShoppingList()`.
5. **Bagaimana object menghasilkan data pada halaman?**
   - Method object dipanggil langsung pada view HTML (misal: `<?= $manager->getTotalCount() ?>` dan `<?= $item->toHtmlCard() ?>`).

---

## 📤 Panduan Push ke GitHub Repository
Jalankan perintah berikut di root folder project:
```bash
git add .
git commit -m "feat: implement Week 6 OOP PHP for Stock Kos (Sprint 05)"
git push origin main
```
Link Repository: **https://github.com/Novallab/Stock-Kos**
