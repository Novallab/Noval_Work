<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Models\Barang;
use App\Models\Program;

/*
|--------------------------------------------------------------------------
| Web Routes (Slide 3: Route pada Laravel)
|--------------------------------------------------------------------------
*/

// Redirect root ke /barangs
Route::get('/', function () {
    return redirect()->route('barangs.index');
});

// Contoh dasar Slide 3: Route string response
Route::get('/program', function () {
    return 'Program StokKos / PresidentKu - Pertemuan 9 Laravel';
});

// Resource CRUD Routes (Task 03)
Route::get('/barangs', [BarangController::class, 'index'])->name('barangs.index');
Route::get('/barangs/create', [BarangController::class, 'create'])->name('barangs.create');
Route::post('/barangs', [BarangController::class, 'store'])->name('barangs.store');
Route::get('/barangs/{id}/edit', [BarangController::class, 'edit'])->name('barangs.edit');
Route::put('/barangs/{id}', [BarangController::class, 'update'])->name('barangs.update');
Route::delete('/barangs/{id}', [BarangController::class, 'destroy'])->name('barangs.destroy');

// Route Uji Eloquent Langsung (Slide 11: Task 02 Uji Eloquent)
Route::get('/test-eloquent', function () {
    // 1. Ambil semua data
    $semuaBarang = Barang::all();

    // 2. Ambil satu data
    $satuBarang = Barang::find(1);

    return response()->json([
        'pesan' => 'Uji Eloquent Berhasil (Slide 11)',
        'Barang_all' => $semuaBarang,
        'Barang_find_1' => $satuBarang,
    ]);
});
