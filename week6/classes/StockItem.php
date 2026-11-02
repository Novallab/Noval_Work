<?php
/**
 * ============================================================================
 * MODUL PERTEMUAN 6: FROM PROCEDURAL TO OBJECT-ORIENTED (SPRINT 05)
 * Class StockItem: Blueprint untuk setiap item inventaris kos
 * ============================================================================
 * Konsep OOP yang diterapkan:
 * 1. Class: Blueprint entitas barang inventaris kos (Slide 2)
 * 2. Property: Variabel penyimpan data barang (Slide 3)
 * 3. Method: Fungsi/perilaku yang dapat dilakukan barang (Slide 3)
 * 4. Constructor: Method __construct() untuk inisialisasi awal (Slide 4)
 * 5. Encapsulation: Property bersifat private dengan getter/setter (Slide 4)
 * 6. Object -> PHP -> HTML: Mengubah data object ke tampilan HTML dinamis (Slide 5)
 */

class StockItem {
    // ==========================================
    // ENCAPSULATION: PRIVATE PROPERTIES (Slide 4)
    // Property hanya dapat diakses langsung di dalam class ini
    // ==========================================
    private int $id;
    private string $name;
    private string $category;
    private int $stock;
    private int $minStock;
    private string $unit;
    private string $location;
    private string $icon;

    /**
     * CONSTRUCTOR (Slide 4)
     * Method khusus yang otomatis dieksekusi saat object baru diinstansiasi
     */
    public function __construct(
        int $id,
        string $name,
        string $category,
        int $stock,
        int $minStock,
        string $unit = 'Pcs',
        string $location = 'Kamar Kos',
        string $icon = '📦'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->category = strtolower($category);
        $this->stock = max(0, $stock);
        $this->minStock = max(1, $minStock);
        $this->unit = $unit;
        $this->location = $location;
        $this->icon = $icon;
    }

    // ==========================================
    // GETTERS (Akses Property Secara Aman - Slide 4)
    // ==========================================
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function getMinStock(): int {
        return $this->minStock;
    }

    public function getUnit(): string {
        return $this->unit;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getIcon(): string {
        return $this->icon;
    }

    // ==========================================
    // SETTERS (Validasi & Perubahan Data Terkendali)
    // ==========================================
    public function setName(string $name): void {
        $this->name = trim($name);
    }

    public function setCategory(string $category): void {
        $this->category = strtolower(trim($category));
    }

    public function setStock(int $stock): void {
        $this->stock = max(0, $stock);
    }

    public function setMinStock(int $minStock): void {
        $this->minStock = max(1, $minStock);
    }

    public function setLocation(string $location): void {
        $this->location = trim($location);
    }

    // ==========================================
    // METHODS: BEHAVIOR / PERILAKU OBJECT (Slide 3 & 8)
    // ==========================================

    /**
     * Menghitung status stok barang
     * @return string 'habis' | 'menipis' | 'aman'
     */
    public function getStatus(): string {
        if ($this->stock === 0) {
            return 'habis';
        } elseif ($this->stock <= $this->minStock) {
            return 'menipis';
        }
        return 'aman';
    }

    public function isOutOfStock(): bool {
        return $this->stock === 0;
    }

    public function isLowStock(): bool {
        return $this->stock <= $this->minStock;
    }

    /**
     * Menambah jumlah stok (+ amount)
     */
    public function increaseStock(int $amount = 1): int {
        $this->stock += max(1, $amount);
        return $this->stock;
    }

    /**
     * Mengurangi jumlah stok (- amount)
     */
    public function decreaseStock(int $amount = 1): int {
        $this->stock = max(0, $this->stock - max(1, $amount));
        return $this->stock;
    }

    /**
     * Menghitung persentase stok untuk visual progress bar
     */
    public function getStockPercentage(): int {
        $maxReference = max($this->minStock * 2, $this->stock, 4);
        return (int) min(100, max(5, round(($this->stock / $maxReference) * 100)));
    }

    /**
     * Badge status stok dalam format HTML Tailwind (Slide 5 & 9)
     */
    public function getStatusBadge(): string {
        $status = $this->getStatus();
        if ($status === 'habis') {
            return '<span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 border border-rose-200">🚨 Stok Habis</span>';
        } elseif ($status === 'menipis') {
            return '<span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">⚠️ Stok Menipis</span>';
        }
        return '<span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">✅ Stok Aman</span>';
    }

    /**
     * Badge kategori dalam format HTML Tailwind
     */
    public function getCategoryBadge(): string {
        switch ($this->category) {
            case 'makanan':
                return '<span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200">🍜 Makanan & Minuman</span>';
            case 'mandi':
                return '<span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200">🧼 Mandi & Cuci</span>';
            case 'kebersihan':
                return '<span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">🧹 Kebersihan</span>';
            case 'p3k':
                return '<span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">🩹 Obat & P3K</span>';
            default:
                return '<span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">📦 Umum</span>';
        }
    }

    /**
     * Konversi object ke associative array untuk penyimpanan JSON
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nama' => $this->name,
            'kategori' => $this->category,
            'stok' => $this->stock,
            'minStok' => $this->minStock,
            'satuan' => $this->unit,
            'lokasi' => $this->location,
            'icon' => $this->icon,
        ];
    }

    /**
     * OBJECT -> PHP -> HTML (Slide 5 & 9)
     * Menghasilkan komponen kartu HTML lengkap langsung dari object ini
     */
    public function toHtmlCard(): string {
        $status = $this->getStatus();
        $percentage = $this->getStockPercentage();

        $barColor = $status === 'habis' ? 'bg-rose-500' : ($status === 'menipis' ? 'bg-amber-500' : 'bg-emerald-500');
        $textColor = $status === 'habis' ? 'text-rose-600' : ($status === 'menipis' ? 'text-amber-600' : 'text-emerald-600');
        $disabledMinus = $this->stock === 0 ? 'disabled class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 text-slate-300 cursor-not-allowed"' : 'class="w-9 h-9 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-300 font-bold transition flex items-center justify-center shadow-xs active:scale-95"';

        $id = $this->id;
        $name = htmlspecialchars($this->name);
        $location = htmlspecialchars($this->location);
        $unit = htmlspecialchars($this->unit);
        $icon = htmlspecialchars($this->icon);

        return "
        <article class=\"bg-white rounded-2xl shadow-xs hover:shadow-lg border border-slate-200/80 transition-all duration-200 flex flex-col justify-between overflow-hidden group\">
          <div class=\"p-5 pb-3\">
            <div class=\"flex items-center justify-between mb-3\">
              {$this->getCategoryBadge()}
              {$this->getStatusBadge()}
            </div>

            <div class=\"flex items-start gap-3\">
              <span class=\"text-3xl p-2.5 bg-slate-50 border border-slate-100 rounded-xl flex-shrink-0 group-hover:scale-110 transition-transform\">
                {$icon}
              </span>
              <div class=\"flex-1 min-w-0\">
                <h3 class=\"font-bold text-slate-900 text-base leading-snug truncate\" title=\"{$name}\">
                  {$name}
                </h3>
                <p class=\"text-xs text-slate-500 mt-1 flex items-center gap-1\">
                  📍 <span class=\"truncate\">{$location}</span>
                </p>
              </div>
            </div>
          </div>

          <div class=\"px-5 py-2\">
            <div class=\"flex justify-between items-baseline mb-1\">
              <span class=\"text-xs text-slate-500\">Stok Saat Ini:</span>
              <span class=\"text-sm font-bold {$textColor}\">
                {$this->stock} <span class=\"text-xs font-normal text-slate-600\">{$unit}</span>
              </span>
            </div>
            <div class=\"w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200/60\">
              <div class=\"{$barColor} h-full rounded-full transition-all duration-300\" style=\"width: {$percentage}%\"></div>
            </div>
            <div class=\"flex justify-between text-[11px] text-slate-500 mt-1\">
              <span>Min. Aman: {$this->minStock} {$unit}</span>
              <span>{$percentage}%</span>
            </div>
          </div>

          <div class=\"p-5 pt-3 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-2\">
            <div class=\"flex items-center gap-1.5\">
              <!-- Form Tombol Kurang (-) via POST -->
              <form method=\"POST\" class=\"inline\">
                <input type=\"hidden\" name=\"action\" value=\"decrease\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                <button type=\"submit\" {$disabledMinus} title=\"Kurangi 1 (Dipakai)\">-</button>
              </form>

              <span class=\"w-8 text-center font-bold text-sm text-slate-800\">
                {$this->stock}
              </span>

              <!-- Form Tombol Tambah (+) via POST -->
              <form method=\"POST\" class=\"inline\">
                <input type=\"hidden\" name=\"action\" value=\"increase\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                <button type=\"submit\" class=\"w-9 h-9 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 font-bold transition flex items-center justify-center shadow-xs active:scale-95\" title=\"Tambah 1 (Restok)\">+</button>
              </form>
            </div>

            <!-- Form Tombol Hapus Barang -->
            <form method=\"POST\" onsubmit=\"return confirm('Yakin ingin menghapus {$name}?');\" class=\"inline\">
              <input type=\"hidden\" name=\"action\" value=\"delete\">
              <input type=\"hidden\" name=\"id\" value=\"{$id}\">
              <button type=\"submit\" class=\"text-xs text-slate-500 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-50 transition\" title=\"Hapus barang\">
                🗑️ Hapus
              </button>
            </form>
          </div>
        </article>
        ";
    }
}
