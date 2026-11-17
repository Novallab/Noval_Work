<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

/**
 * Controller Barang (Task 03: Build Program/Barang CRUD)
 * Mengintegrasikan Eloquent ORM dengan View Blade.
 */
class BarangController extends Controller
{
    /**
     * READ: Menampilkan semua data dari database (Slide 6 & 12)
     */
    public function index()
    {
        // Eloquent ORM mengambil seluruh data dari tabel barangs
        $barangs = Barang::all();

        return view('barangs.index', compact('barangs'));
    }

    /**
     * CREATE: Menampilkan form tambah barang
     */
    public function create()
    {
        return view('barangs.create');
    }

    /**
     * CREATE (Store): Menyimpan data baru ke database (Slide 6 & 12)
     */
    public function store(Request $request)
    {
        // Menyimpan data menggunakan Eloquent create
        Barang::create([
            'nama'      => $request->input('nama'),
            'kategori'  => $request->input('kategori'),
            'stok'      => (int) $request->input('stok'),
            'satuan'    => $request->input('satuan'),
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()->route('barangs.index')->with('success', 'Barang baru berhasil ditambahkan ke database!');
    }

    /**
     * UPDATE (Edit): Menampilkan form edit berdasarkan ID (Slide 6 & 12)
     */
    public function edit($id)
    {
        // Eloquent ORM mengambil satu record berdasarkan ID (Slide 6: Program::find(1))
        $barang = Barang::findOrFail($id);

        return view('barangs.edit', compact('barang'));
    }

    /**
     * UPDATE: Memperbarui data yang ada di database
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama'      => $request->input('nama'),
            'kategori'  => $request->input('kategori'),
            'stok'      => (int) $request->input('stok'),
            'satuan'    => $request->input('satuan'),
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()->route('barangs.index')->with('success', 'Data barang berhasil diperbarui di database!');
    }

    /**
     * DELETE: Menghapus data dari database (Slide 7 & 12)
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Data barang berhasil dihapus dari database!');
    }
}
