<?php
/**
 * ============================================================================
 * STOK KOS - SMART INVENTORY TRACKER (OOP PHP EDITION)
 * Pertemuan 6 Web Programming: From Procedural to Object-Oriented (Sprint 05)
 * ============================================================================
 * Mengimplementasikan:
 * - Instansiasi Object dari Class InventoryManager & StockItem (Slide 2)
 * - Pemanggilan Method pada Object (Slide 3)
 * - Encapsulation Data & Keamanan Akses (Slide 4)
 * - Object -> PHP -> Dynamic HTML Rendering (Slide 5 & 9)
 * - Form Action Handling (Tambah, Ubah Stok, Hapus, Restok, Reset)
 */

session_start();

require_once __DIR__ . '/classes/StockItem.php';
require_once __DIR__ . '/classes/InventoryManager.php';

// Instansiasi Object Utama (Slide 2)
$manager = new InventoryManager(__DIR__ . '/data/stock.json');

// Pesan Notifikasi (Toast Feedback)
$toastMessage = $_SESSION['toast_msg'] ?? null;
$toastType = $_SESSION['toast_type'] ?? 'success';
unset($_SESSION['toast_msg'], $_SESSION['toast_type']);

// ==========================================
// ACTION HANDLING (POST & GET)
// Memanggil method pada object berdasarkan aksi user (Slide 3 & 8)
// ==========================================
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'increase') {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $manager->findById($id);
        if ($item && $manager->increaseStock($id)) {
            $_SESSION['toast_msg'] = "Stok {$item->getName()} berhasil ditambah (+1)";
            $_SESSION['toast_type'] = 'success';
        }
    } elseif ($action === 'decrease') {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $manager->findById($id);
        if ($item && $manager->decreaseStock($id)) {
            if ($item->getStock() === 0) {
                $_SESSION['toast_msg'] = "Stok {$item->getName()} habis! Masuk ke daftar belanja.";
                $_SESSION['toast_type'] = 'alert';
            } elseif ($item->isLowStock()) {
                $_SESSION['toast_msg'] = "Peringatan: Stok {$item->getName()} menipis!";
                $_SESSION['toast_type'] = 'alert';
            } else {
                $_SESSION['toast_msg'] = "Stok {$item->getName()} berkurang (-1)";
                $_SESSION['toast_type'] = 'success';
            }
        }
    } elseif ($action === 'restock') {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $manager->findById($id);
        if ($item && $manager->quickRestock($id)) {
            $_SESSION['toast_msg'] = "Berhasil restok {$item->getName()}!";
            $_SESSION['toast_type'] = 'success';
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $manager->findById($id);
        $name = $item ? $item->getName() : 'Barang';
        if ($manager->deleteItem($id)) {
            $_SESSION['toast_msg'] = "Barang {$name} telah dihapus dari inventaris.";
            $_SESSION['toast_type'] = 'alert';
        }
    } elseif ($action === 'add') {
        $name = trim($_POST['nama'] ?? '');
        $category = trim($_POST['kategori'] ?? 'umum');
        $stock = (int) ($_POST['stok'] ?? 0);
        $minStock = (int) ($_POST['min_stok'] ?? 2);
        $unit = trim($_POST['satuan'] ?? 'Pcs');
        $location = trim($_POST['lokasi'] ?? 'Kamar Kos');
        $icon = trim($_POST['icon'] ?? '📦');

        if (!empty($name)) {
            // Memanggil method addItem pada object InventoryManager (Slide 3)
            $manager->addItem($name, $category, $stock, $minStock, $unit, $location, $icon);
            $_SESSION['toast_msg'] = "Barang \"{$name}\" berhasil ditambahkan via OOP PHP!";
            $_SESSION['toast_type'] = 'success';
        }
    } elseif ($action === 'reset') {
        $manager->resetToDefault();
        $_SESSION['toast_msg'] = "Data stok kos berhasil di-reset ke data default.";
        $_SESSION['toast_type'] = 'success';
    }

    // Redirect untuk menghindari form resubmission (PRG Pattern)
    $redirectUrl = 'index.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
    header('Location: ' . $redirectUrl);
    exit;
}

// ==========================================
// FILTER, PENCARIAN & SORTING DENGAN METHOD OBJECT
// ==========================================
$categoryFilter = $_GET['category'] ?? 'all';
$searchQuery = trim($_GET['search'] ?? '');
$sortOption = $_GET['sort'] ?? 'default';

// Memanggil method filterAndSort pada object (Slide 8)
$displayedItems = $manager->filterAndSort($categoryFilter, $searchQuery, $sortOption);
$shoppingList = $manager->getShoppingList();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Kos (OOP PHP) - Smart Inventory & Tracker Kebutuhan Kos</title>
  <link rel="stylesheet" href="./css/output.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

  <!-- ==========================================
       HEADER & NAVBAR (HTML5 Semantik)
  =========================================== -->
  <header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        
        <!-- Logo & Branding -->
        <div class="flex items-center gap-3">
          <span class="text-2xl p-2 bg-blue-600/30 border border-blue-500/40 rounded-xl">📦</span>
          <div>
            <h1 class="text-lg font-extrabold tracking-tight text-white flex items-center gap-2">
              StokKos<span class="text-blue-400">.id</span>
              <span class="text-[10px] font-semibold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-500/30 px-2 py-0.5 rounded-full">
                Week 6 &bull; OOP PHP
              </span>
            </h1>
            <p class="text-[11px] text-slate-400 hidden sm:block">Object-Oriented Web Application (Sprint 05)</p>
          </div>
        </div>

        <!-- Real-time Alert Badge (Hasil Kalkulasi Object PHP - Slide 3) -->
        <div class="hidden md:flex items-center">
          <?php if ($manager->getNeedsRestockCount() > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500 text-white shadow-xs animate-pulse">
              ⚠️ <?= $manager->getNeedsRestockCount() ?> Perlu Restok
            </span>
          <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-600 text-white shadow-xs">
              ✅ Semua Stok Aman (<?= $manager->getTotalCount() ?> Item)
            </span>
          <?php endif; ?>
        </div>

        <!-- Navigasi & Aksi -->
        <nav class="flex items-center gap-3">
          <button 
            type="button" 
            onclick="document.getElementById('oop-modal').classList.remove('hidden')" 
            class="hidden sm:flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-blue-300 text-xs font-semibold px-3 py-2 rounded-xl border border-slate-700 transition"
          >
            <span>ℹ️</span> <span>Alur OOP</span>
          </button>

          <a 
            href="#tambah-barang" 
            class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs sm:text-sm px-3.5 py-2 rounded-xl shadow-sm transition active:scale-95 flex items-center gap-1.5"
          >
            <span>+</span> <span>Tambah Barang</span>
          </a>
        </nav>

      </div>
    </div>
  </header>

  <!-- ==========================================
       MAIN CONTENT
  =========================================== -->
  <main class="flex-1">

    <!-- HERO SECTION & STATISTIK DARI OBJECT (Slide 9 & 10) -->
    <section id="dashboard" class="bg-gradient-to-b from-slate-900 via-slate-850 to-slate-900 text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          
          <!-- Teks Pengantar -->
          <div class="lg:col-span-7 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs font-semibold">
              <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
              Pertemuan 6 Web Programming &bull; From Procedural to Object-Oriented (Sprint 05)
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white">
              Kelola Stok Kos dengan <br class="hidden sm:inline">
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-indigo-300">
                Object-Oriented PHP
              </span>
            </h2>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-2xl">
              Aplikasi telah direfaktor menjadi arsitektur OOP murni: data dibungkus dalam class <code class="text-cyan-300 bg-slate-800 px-1.5 py-0.5 rounded font-mono text-xs">StockItem</code> dengan private properties & constructor, dan dikelola oleh <code class="text-cyan-300 bg-slate-800 px-1.5 py-0.5 rounded font-mono text-xs">InventoryManager</code>.
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-2">
              <a href="#daftar-stok" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-blue-600/30 transition">
                Lihat Semua Stok &darr;
              </a>
              <button 
                type="button" 
                onclick="document.getElementById('oop-modal').classList.remove('hidden')" 
                class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-medium text-sm px-4 py-2.5 rounded-xl border border-slate-700 transition"
              >
                💡 Penjelasan Demo OOP (Slide 10)
              </button>
              
              <!-- Form Reset Data -->
              <form method="POST" onsubmit="return confirm('Kembalikan stok ke data awal kos?');" class="inline">
                <input type="hidden" name="action" value="reset">
                <button type="submit" class="bg-slate-800/80 hover:bg-rose-900/40 text-slate-400 hover:text-rose-300 font-medium text-sm px-3.5 py-2.5 rounded-xl border border-slate-700 transition" title="Reset ke data awal">
                  🔄 Reset
                </button>
              </form>
            </div>
          </div>

          <!-- Banner Ilustrasi Lokal (img/img1.jpg) -->
          <div class="lg:col-span-5">
            <div class="bg-slate-800/60 p-4 rounded-2xl border border-slate-700/60 backdrop-blur-xs shadow-2xl flex flex-col items-center">
              <div class="w-full h-44 sm:h-52 rounded-xl overflow-hidden bg-slate-900 border border-slate-700/80 flex items-center justify-center p-2">
                <img 
                  src="./img/img1.jpg" 
                  alt="Ilustrasi Sistem Inventaris Web Modern" 
                  class="w-full h-full object-contain"
                >
              </div>
              <div class="mt-3 flex items-center justify-between w-full text-xs text-slate-400 px-1">
                <span class="flex items-center gap-1.5 font-medium text-slate-300">
                  ⚡ PHP 8.2 OOP &bull; Classes & Encapsulation
                </span>
                <span class="text-blue-400 font-semibold">Tailwind CSS v4</span>
              </div>
            </div>
          </div>

        </div>

        <!-- KARTU STATISTIK (Dihasilkan melalui Method Object - Slide 3 & 9) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
          
          <!-- Stat 1: Total Barang -->
          <div class="bg-slate-800/90 border border-slate-700 p-4 sm:p-5 rounded-2xl shadow-sm flex items-center gap-4">
            <span class="text-3xl p-3 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-xl">📦</span>
            <div>
              <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Barang</p>
              <h3 class="text-2xl sm:text-3xl font-black text-white mt-0.5">
                <?= $manager->getTotalCount() ?>
              </h3>
            </div>
          </div>

          <!-- Stat 2: Stok Aman -->
          <div class="bg-slate-800/90 border border-slate-700 p-4 sm:p-5 rounded-2xl shadow-sm flex items-center gap-4">
            <span class="text-3xl p-3 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl">✅</span>
            <div>
              <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Stok Aman</p>
              <h3 class="text-2xl sm:text-3xl font-black text-emerald-400 mt-0.5">
                <?= $manager->getAmanCount() ?>
              </h3>
            </div>
          </div>

          <!-- Stat 3: Stok Menipis -->
          <div class="bg-slate-800/90 border border-slate-700 p-4 sm:p-5 rounded-2xl shadow-sm flex items-center gap-4">
            <span class="text-3xl p-3 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-xl">⚠️</span>
            <div>
              <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Stok Menipis</p>
              <h3 class="text-2xl sm:text-3xl font-black text-amber-400 mt-0.5">
                <?= $manager->getMenipisCount() ?>
              </h3>
            </div>
          </div>

          <!-- Stat 4: Stok Habis -->
          <div class="bg-slate-800/90 border border-slate-700 p-4 sm:p-5 rounded-2xl shadow-sm flex items-center gap-4">
            <span class="text-3xl p-3 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl">🚨</span>
            <div>
              <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Habis Total</p>
              <h3 class="text-2xl sm:text-3xl font-black text-rose-400 mt-0.5">
                <?= $manager->getHabisCount() ?>
              </h3>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- SECTION INTERAKTIF: PENCARIAN, FILTER, DAN DAFTAR STOK OOP -->
    <section id="daftar-stok" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      
      <!-- Kontrol Pencarian & Filter -->
      <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs mb-8 space-y-4">
        
        <form method="GET" class="space-y-4">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Inventaris Kebutuhan Kos (Data-Driven by OOP)
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Setiap item adalah instance dari class <code class="font-mono font-bold text-slate-700">StockItem</code>. Tekan [-] atau [+] untuk memodifikasi state object.
              </p>
            </div>

            <!-- Kotak Pencarian -->
            <div class="relative w-full md:w-80">
              <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">
                🔍
              </span>
              <input 
                type="text" 
                name="search" 
                value="<?= htmlspecialchars($searchQuery) ?>" 
                placeholder="Cari mie, sabun, galon..." 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
              >
            </div>
          </div>

          <!-- Filter Tab Kategori & Pengurutan -->
          <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
            
            <!-- Category Tabs Links -->
            <div class="flex flex-wrap items-center gap-1.5">
              <?php
              $cats = [
                'all' => 'Semua',
                'perlu_beli' => '⚠️ Perlu Restok',
                'makanan' => '🍜 Makanan & Minuman',
                'mandi' => '🧼 Mandi & Cuci',
                'kebersihan' => '🧹 Kebersihan',
                'p3k' => '🩹 Obat & P3K'
              ];
              foreach ($cats as $key => $label):
                $isActive = ($categoryFilter === $key);
                $btnClass = $isActive 
                  ? 'bg-slate-900 text-white shadow-xs' 
                  : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200';
              ?>
                <a 
                  href="?category=<?= $key ?>&search=<?= urlencode($searchQuery) ?>&sort=<?= urlencode($sortOption) ?>#daftar-stok"
                  class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition <?= $btnClass ?>"
                >
                  <?= $label ?>
                </a>
              <?php endforeach; ?>
            </div>

            <!-- Sort Dropdown -->
            <div class="flex items-center gap-2 ml-auto">
              <input type="hidden" name="category" value="<?= htmlspecialchars($categoryFilter) ?>">
              <label for="sort-select" class="text-xs text-slate-500 whitespace-nowrap">Urutkan:</label>
              <select 
                id="sort-select" 
                name="sort" 
                onchange="this.form.submit()" 
                class="text-xs bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/30 text-slate-700"
              >
                <option value="default" <?= $sortOption === 'default' ? 'selected' : '' ?>>Bawaan</option>
                <option value="stok-asc" <?= $sortOption === 'stok-asc' ? 'selected' : '' ?>>Stok Paling Sedikit (Prioritas)</option>
                <option value="stok-desc" <?= $sortOption === 'stok-desc' ? 'selected' : '' ?>>Stok Paling Banyak</option>
                <option value="nama-asc" <?= $sortOption === 'nama-asc' ? 'selected' : '' ?>>Nama Barang (A - Z)</option>
              </select>
            </div>

          </div>
        </form>

      </div>

      <!-- KARTU INVENTARIS GRID (OBJECT -> PHP -> HTML - Slide 5 & 9) -->
      <?php if (empty($displayedItems)): ?>
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 p-8 my-6">
          <span class="text-4xl block mb-3">🔍</span>
          <h3 class="text-lg font-bold text-slate-800">Tidak ada barang yang cocok</h3>
          <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
            Coba ganti kata kunci pencarian Anda atau reset filter untuk melihat semua barang kos.
          </p>
          <a href="index.php#daftar-stok" class="inline-block mt-4 text-xs font-bold bg-blue-600 text-white px-4 py-2 rounded-xl">
            Tampilkan Semua Barang
          </a>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <?php foreach ($displayedItems as $item): ?>
            <!-- Memanggil method toHtmlCard() dari instance StockItem (Slide 5) -->
            <?= $item->toHtmlCard() ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </section>

    <!-- SECTION DUA KOLOM: FORM TAMBAH STOK & DAFTAR BELANJA DARI OBJECT -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: FORM TAMBAH BARANG BARU (POST ACTION) -->
        <div id="tambah-barang" class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
          <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <span class="text-2xl p-2.5 bg-blue-50 border border-blue-200 rounded-xl">✨</span>
            <div>
              <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Barang ke Stok Kos (OOP)</h2>
              <p class="text-xs text-slate-500">Data form akan diinstansiasi menjadi instance <code class="font-mono text-blue-600">StockItem</code> baru.</p>
            </div>
          </div>

          <form method="POST" action="index.php#daftar-stok" class="space-y-4">
            <input type="hidden" name="action" value="add">
            
            <!-- Nama Barang -->
            <div>
              <label for="input-nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Nama Barang <span class="text-rose-500">*</span>
              </label>
              <input 
                type="text" 
                id="input-nama" 
                name="nama" 
                placeholder="Contoh: Kopi Sachet Good Day / Telur 1/2 Kg" 
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
                required
              >
            </div>

            <!-- Kategori & Emoji Icon -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              
              <div class="sm:col-span-2">
                <label for="input-kategori" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                  Kategori <span class="text-rose-500">*</span>
                </label>
                <select 
                  id="input-kategori" 
                  name="kategori" 
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition text-slate-800"
                  required
                >
                  <option value="makanan">🍜 Makanan & Minuman</option>
                  <option value="mandi">🧼 Mandi & Cuci Pakaian</option>
                  <option value="kebersihan">🧹 Kebersihan Kamar</option>
                  <option value="p3k">🩹 Obat-obatan & P3K</option>
                </select>
              </div>

              <div>
                <label for="input-icon" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                  Ikon Emoji
                </label>
                <input 
                  type="text" 
                  id="input-icon" 
                  name="icon" 
                  value="📦" 
                  maxlength="4"
                  class="w-full px-3.5 py-2.5 text-center text-lg bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
                >
              </div>

            </div>

            <!-- Jumlah Stok, Batas Minimum, dan Satuan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              
              <div>
                <label for="input-stok" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                  Jumlah Stok <span class="text-rose-500">*</span>
                </label>
                <input 
                  type="number" 
                  id="input-stok" 
                  name="stok" 
                  min="0" 
                  value="1" 
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
                  required
                >
              </div>

              <div>
                <label for="input-min-stok" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                  Batas Min. Aman <span class="text-rose-500">*</span>
                </label>
                <input 
                  type="number" 
                  id="input-min-stok" 
                  name="min_stok" 
                  min="1" 
                  value="2" 
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
                  required
                >
              </div>

              <div>
                <label for="input-satuan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                  Satuan <span class="text-rose-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="input-satuan" 
                  name="satuan" 
                  placeholder="Pcs/Bungkus/Kg" 
                  value="Bungkus"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
                  required
                >
              </div>

            </div>

            <!-- Lokasi Simpan -->
            <div>
              <label for="input-lokasi" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                Lokasi Simpan di Kos
              </label>
              <input 
                type="text" 
                id="input-lokasi" 
                name="lokasi" 
                placeholder="Misal: Rak Atas Meja Belajar / Lemari Bawah" 
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600 transition"
              >
            </div>

            <!-- Tombol Submit Form -->
            <button 
              type="submit" 
              class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md shadow-blue-600/20 transition active:scale-95 flex items-center justify-center gap-2 mt-4"
            >
              <span>+</span> <span>Simpan Barang via Method addItem()</span>
            </button>

          </form>
        </div>

        <!-- KOLOM KANAN: DAFTAR BELANJA OTOMATIS (Dihasilkan dari Object - Slide 10) -->
        <div id="daftar-belanja" class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
              <div class="flex items-center gap-2.5">
                <span class="text-2xl p-2 bg-amber-50 border border-amber-200 rounded-xl">🛒</span>
                <div>
                  <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    Daftar Belanja Kos (OOP)
                    <span class="text-xs text-rose-600 font-semibold">(<?= count($shoppingList) ?>)</span>
                  </h3>
                  <p class="text-xs text-slate-500">Method <code class="font-mono text-slate-700">$manager->getShoppingList()</code></p>
                </div>
              </div>
            </div>

            <!-- Daftar Item Belanja -->
            <div class="space-y-2.5 max-h-[360px] overflow-y-auto pr-1">
              <?php if (empty($shoppingList)): ?>
                <div class="p-6 text-center text-slate-500">
                  <span class="text-3xl block mb-2">🎉</span>
                  <p class="font-medium text-sm text-slate-700">Persediaan kos lengkap!</p>
                  <p class="text-xs text-slate-600 mt-0.5">Semua barang memiliki stok di atas batas minimum aman.</p>
                </div>
              <?php else: ?>
                <?php foreach ($shoppingList as $item): 
                  $isHabis = $item->isOutOfStock();
                  $statusText = $isHabis ? 'text-rose-600 font-medium' : 'text-amber-600';
                ?>
                  <div class="p-3 bg-white rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-3 hover:border-slate-300 transition">
                    <div class="flex items-center gap-2.5 min-w-0">
                      <span class="text-xl"><?= htmlspecialchars($item->getIcon()) ?></span>
                      <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($item->getName()) ?></p>
                        <p class="text-xs <?= $statusText ?>">
                          Sisa: <?= $item->getStock() ?> <?= htmlspecialchars($item->getUnit()) ?> (Min: <?= $item->getMinStock() ?>)
                        </p>
                      </div>
                    </div>

                    <!-- Form Restok Cepat -->
                    <form method="POST" class="inline">
                      <input type="hidden" name="action" value="restock">
                      <input type="hidden" name="id" value="<?= $item->getId() ?>">
                      <button 
                        type="submit" 
                        class="flex-shrink-0 text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition active:scale-95"
                        title="Klik setelah belanja"
                      >
                        + Beli & Restok
                      </button>
                    </form>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Rangkuman Alur OOP (Slide 10) -->
          <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1.5">
            <p class="font-bold text-slate-800 flex items-center gap-1.5">
              💡 Karakteristik OOP di Aplikasi Stock Kos:
            </p>
            <p>1. <strong>Class Blueprint:</strong> Entitas barang didefinisikan dalam <code class="bg-slate-200 px-1 rounded text-slate-800 font-mono">StockItem</code>.</p>
            <p>2. <strong>Encapsulation:</strong> Data bersifat <code class="bg-slate-200 px-1 rounded text-slate-800 font-mono">private</code> dan hanya diakses melalui <code class="bg-slate-200 px-1 rounded text-slate-800 font-mono">getter/setter</code>.</p>
            <p>3. <strong>Object -> HTML:</strong> Kartu dirender langsung melalui method <code class="bg-slate-200 px-1 rounded text-slate-800 font-mono">$item->toHtmlCard()</code>.</p>
          </div>
        </div>

      </div>
    </section>

  </main>

  <!-- ==========================================
       TOAST NOTIFICATION (Feedback Visual)
  =========================================== -->
  <?php if ($toastMessage): ?>
    <div id="toast" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl text-sm font-medium border <?= $toastType === 'success' ? 'bg-slate-900 text-white border-slate-700' : 'bg-rose-600 text-white border-rose-700' ?>">
      <span><?= $toastType === 'success' ? '✅' : '⚠️' ?></span>
      <span><?= htmlspecialchars($toastMessage) ?></span>
    </div>
    <script>
      setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) {
          toast.style.transition = 'opacity 0.5s ease';
          toast.style.opacity = '0';
          setTimeout(() => toast.remove(), 500);
        }
      }, 3500);
    </script>
  <?php endif; ?>

  <!-- ==========================================
       MODAL PENJELASAN ALUR OOP (SLIDE 10: DEMO OOP PROCESS)
  =========================================== -->
  <div id="oop-modal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-slate-200">
      
      <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
        <div class="flex items-center gap-3">
          <span class="text-2xl p-2 bg-blue-50 border border-blue-200 rounded-xl">📘</span>
          <div>
            <h3 class="text-lg font-bold text-slate-900">Pembahasan OOP: Pertemuan 6 (Sprint 05)</h3>
            <p class="text-xs text-slate-500">Jawaban 5 Pertanyaan Diskusi Modul &quot;Demo: Show Your OOP Process&quot; (Slide 10)</p>
          </div>
        </div>
        <button 
          type="button" 
          onclick="document.getElementById('oop-modal').classList.add('hidden')"
          class="text-slate-400 hover:text-slate-700 text-xl font-bold p-1"
        >
          ✕
        </button>
      </div>

      <div class="space-y-4 text-xs sm:text-sm text-slate-600 leading-relaxed">
        
        <!-- Tanya 1 -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
          <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">1</span>
            Class apa yang dibuat?
          </h4>
          <p class="text-slate-600">
            Dibuat dua class utama:
            <br>&bull; <strong class="text-slate-800">StockItem:</strong> Blueprint untuk memodelkan setiap barang inventaris kos.
            <br>&bull; <strong class="text-slate-800">InventoryManager:</strong> Pengelola koleksi object barang, bertugas memuat/menyimpan data JSON, memfilter, dan menghitung statistik.
          </p>
        </div>

        <!-- Tanya 2 -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
          <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">2</span>
            Object apa yang digunakan?
          </h4>
          <p class="text-slate-600">
            Object utama yang digunakan pada halaman adalah <strong class="text-slate-800">$manager</strong> (instance dari <code class="font-mono text-blue-600 font-bold">InventoryManager</code>) dan kumpulan object <strong class="text-slate-800">$item</strong> (instance dari <code class="font-mono text-blue-600 font-bold">StockItem</code>) yang merepresentasikan setiap mie, galon air, sabun, dan kebutuhan kos lainnya.
          </p>
        </div>

        <!-- Tanya 3 -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
          <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">3</span>
            Property apa yang dimiliki object?
          </h4>
          <p class="text-slate-600">
            Pada <code class="font-mono text-blue-600">StockItem</code> semua property bersifat <strong class="text-slate-800">private</strong> (Encapsulation):
            <code class="font-mono">$id, $name, $category, $stock, $minStock, $unit, $location, $icon</code>.
          </p>
        </div>

        <!-- Tanya 4 -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
          <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">4</span>
            Method apa yang dibuat?
          </h4>
          <p class="text-slate-600">
            &bull; Constructor: <code class="font-mono">__construct()</code><br>
            &bull; Getters & Setters: <code class="font-mono">getId(), getName(), getStock(), setStock()</code><br>
            &bull; Domain Logic: <code class="font-mono">getStatus(), isLowStock(), increaseStock(), decreaseStock()</code><br>
            &bull; HTML Generator: <code class="font-mono">toHtmlCard(), getStatusBadge(), getCategoryBadge()</code><br>
            &bull; Manager Methods: <code class="font-mono">addItem(), deleteItem(), filterAndSort(), getShoppingList()</code>
          </p>
        </div>

        <!-- Tanya 5 -->
        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
          <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">5</span>
            Bagaimana object menghasilkan data pada halaman?
          </h4>
          <p class="text-slate-600">
            Halaman web memanggil method object secara langsung (Slide 5):
            Contoh: <code class="font-mono text-blue-600">&lt;?= $manager->getTotalCount() ?&gt;</code> untuk angka total, dan <code class="font-mono text-blue-600">&lt;?= $item->toHtmlCard() ?&gt;</code> di dalam perulangan <code class="font-mono">foreach ($displayedItems as $item)</code> untuk menghasilkan kartu HTML interaktif secara dinamis.
          </p>
        </div>

      </div>

      <div class="mt-6 flex justify-end">
        <button 
          type="button" 
          onclick="document.getElementById('oop-modal').classList.add('hidden')"
          class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition"
        >
          Tutup Pembahasan
        </button>
      </div>

    </div>
  </div>

  <!-- ==========================================
       FOOTER (HTML5 Semantik)
  =========================================== -->
  <footer class="bg-slate-900 text-slate-400 py-10 px-4 sm:px-6 lg:px-8 border-t border-slate-800 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-6">
      
      <div class="flex items-center gap-3">
        <span class="text-2xl p-2 bg-slate-800 border border-slate-700 rounded-xl">📦</span>
        <div>
          <p class="text-sm font-bold text-white">StokKos.id &bull; Project Praktikum Web</p>
          <p class="text-xs text-slate-500">Pertemuan 6: From Procedural to Object-Oriented (Sprint 05)</p>
        </div>
      </div>

      <div class="text-center sm:text-right text-xs text-slate-400 space-y-1">
        <p>GitHub Repository: <a href="https://github.com/Novallab/Stock-Kos" target="_blank" class="text-blue-400 hover:underline font-semibold">https://github.com/Novallab/Stock-Kos</a></p>
        <p class="text-slate-500">PHP 8.2 OOP &bull; HTML5 Semantik &bull; Tailwind CSS v4</p>
      </div>

    </div>
  </footer>

</body>
</html>
