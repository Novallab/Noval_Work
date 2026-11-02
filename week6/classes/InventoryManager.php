<?php
/**
 * ============================================================================
 * MODUL PERTEMUAN 6: FROM PROCEDURAL TO OBJECT-ORIENTED (SPRINT 05)
 * Class InventoryManager: Pengelola koleksi object StockItem
 * ============================================================================
 * Mengimplementasikan:
 * - Pengelolaan kumpulan object (Collection of Objects - Slide 10)
 * - Encapsulation penyimpanan data (JSON persistence)
 * - Logika kalkulasi statistik, penyaringan (filtering), dan pencarian
 */

require_once __DIR__ . '/StockItem.php';

class InventoryManager {
    /**
     * @var StockItem[] Array of StockItem objects (Slide 2 & 10)
     */
    private array $items = [];

    /**
     * Path ke file data JSON
     */
    private string $storagePath;

    /**
     * CONSTRUCTOR (Slide 4)
     * Menginisialisasi manager dan memuat data dari file storage
     */
    public function __construct(string $storagePath = __DIR__ . '/../data/stock.json') {
        $this->storagePath = $storagePath;
        $this->loadData();
    }

    /**
     * Memuat data JSON dan mengonversinya menjadi kumpulan Object StockItem
     */
    public function loadData(): void {
        $this->items = [];

        if (file_exists($this->storagePath)) {
            $json = file_get_contents($this->storagePath);
            $data = json_decode($json, true);

            if (is_array($data)) {
                foreach ($data as $row) {
                    // Instansiasi Object dari Class StockItem (Slide 2)
                    $this->items[] = new StockItem(
                        (int) ($row['id'] ?? time()),
                        (string) ($row['nama'] ?? 'Tanpa Nama'),
                        (string) ($row['kategori'] ?? 'umum'),
                        (int) ($row['stok'] ?? 0),
                        (int) ($row['minStok'] ?? 2),
                        (string) ($row['satuan'] ?? 'Pcs'),
                        (string) ($row['lokasi'] ?? 'Kamar Kos'),
                        (string) ($row['icon'] ?? '📦')
                    );
                }
            }
        }
    }

    /**
     * Menyimpan seluruh kumpulan object kembali ke file JSON
     */
    public function saveData(): bool {
        $dataArray = [];
        foreach ($this->items as $item) {
            $dataArray[] = $item->toArray();
        }

        $dir = dirname($this->storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return file_put_contents(
            $this->storagePath,
            json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ) !== false;
    }

    /**
     * Mengembalikan seluruh object StockItem
     * @return StockItem[]
     */
    public function getAll(): array {
        return $this->items;
    }

    /**
     * Mencari object berdasarkan ID
     */
    public function findById(int $id): ?StockItem {
        foreach ($this->items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Menambah barang baru ke inventaris kos
     */
    public function addItem(
        string $name,
        string $category,
        int $stock,
        int $minStock,
        string $unit,
        string $location,
        string $icon = '📦'
    ): StockItem {
        $newId = (int) (microtime(true) * 1000) + rand(1, 999);
        $newItem = new StockItem($newId, $name, $category, $stock, $minStock, $unit, $location, $icon);

        // Tambahkan ke awal array (unshift)
        array_unshift($this->items, $newItem);
        $this->saveData();

        return $newItem;
    }

    /**
     * Menambah jumlah stok (+1)
     */
    public function increaseStock(int $id, int $amount = 1): bool {
        $item = $this->findById($id);
        if ($item) {
            $item->increaseStock($amount);
            return $this->saveData();
        }
        return false;
    }

    /**
     * Mengurangi jumlah stok (-1)
     */
    public function decreaseStock(int $id, int $amount = 1): bool {
        $item = $this->findById($id);
        if ($item) {
            $item->decreaseStock($amount);
            return $this->saveData();
        }
        return false;
    }

    /**
     * Restok cepat dari daftar belanja (misal beli 2x minStok)
     */
    public function quickRestock(int $id): bool {
        $item = $this->findById($id);
        if ($item) {
            $tambah = $item->getMinStock() > 1 ? $item->getMinStock() * 2 : 2;
            $item->increaseStock($tambah);
            return $this->saveData();
        }
        return false;
    }

    /**
     * Menghapus barang dari inventaris
     */
    public function deleteItem(int $id): bool {
        $initialCount = count($this->items);
        $this->items = array_values(array_filter(
            $this->items,
            fn(StockItem $item) => $item->getId() !== $id
        ));

        if (count($this->items) !== $initialCount) {
            return $this->saveData();
        }
        return false;
    }

    // ==========================================
    // METHODS: KALKULASI STATISTIK RINGKASAN
    // ==========================================
    public function getTotalCount(): int {
        return count($this->items);
    }

    public function getAmanCount(): int {
        return count(array_filter($this->items, fn(StockItem $i) => $i->getStatus() === 'aman'));
    }

    public function getMenipisCount(): int {
        return count(array_filter($this->items, fn(StockItem $i) => $i->getStatus() === 'menipis'));
    }

    public function getHabisCount(): int {
        return count(array_filter($this->items, fn(StockItem $i) => $i->getStatus() === 'habis'));
    }

    public function getNeedsRestockCount(): int {
        return $this->getMenipisCount() + $this->getHabisCount();
    }

    /**
     * Mendapatkan daftar barang yang perlu dibeli (Stok <= Min. Aman)
     * @return StockItem[]
     */
    public function getShoppingList(): array {
        return array_values(array_filter(
            $this->items,
            fn(StockItem $item) => $item->isLowStock()
        ));
    }

    /**
     * Menyaring dan mengurutkan kumpulan object (Filter & Sort)
     * @return StockItem[]
     */
    public function filterAndSort(string $category = 'all', string $search = '', string $sort = 'default'): array {
        $filtered = array_filter($this->items, function(StockItem $item) use ($category, $search) {
            // Filter Kategori
            $matchCat = ($category === 'all')
                || ($category === 'perlu_beli' && $item->isLowStock())
                || ($item->getCategory() === $category);

            // Filter Pencarian
            $matchSearch = empty($search)
                || (stripos($item->getName(), $search) !== false)
                || (stripos($item->getLocation(), $search) !== false);

            return $matchCat && $matchSearch;
        });

        $filtered = array_values($filtered);

        // Pengurutan
        if ($sort === 'stok-asc') {
            usort($filtered, fn(StockItem $a, StockItem $b) => $a->getStock() <=> $b->getStock());
        } elseif ($sort === 'stok-desc') {
            usort($filtered, fn(StockItem $a, StockItem $b) => $b->getStock() <=> $a->getStock());
        } elseif ($sort === 'nama-asc') {
            usort($filtered, fn(StockItem $a, StockItem $b) => strcasecmp($a->getName(), $b->getName()));
        }

        return $filtered;
    }

    /**
     * Mengembalikan data ke kondisi awal (Reset Database)
     */
    public function resetToDefault(): void {
        $defaultJson = __DIR__ . '/../../Week 4/data/stock.json';
        if (file_exists($defaultJson)) {
            copy($defaultJson, $this->storagePath);
            $this->loadData();
        }
    }
}
