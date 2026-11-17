@extends('layouts.app')

@section('title', 'Tambah Barang Baru - StokKos Laravel')

@section('content')
<div class="max-w-xl mx-auto bg-slate-950 p-6 sm:p-8 rounded-3xl border border-slate-800 space-y-6 shadow-xl">
    
    <div class="border-b border-slate-800 pb-4">
        <h2 class="text-xl font-black text-white">Tambah Barang Baru (Create)</h2>
        <p class="text-xs text-slate-400 mt-1">Menyimpan data baru ke database via <code class="text-red-400 font-mono">Barang::create()</code></p>
    </div>

    <form action="{{ route('barangs.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Nama Barang</label>
            <input type="text" name="nama" required placeholder="Contoh: Indomie Goreng / Sabun Mandi" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-red-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-red-500">
                    <option value="Makanan">🍜 Makanan & Minuman</option>
                    <option value="Mandi">🧼 Mandi & Cuci</option>
                    <option value="Kebersihan">🧹 Kebersihan</option>
                    <option value="P3K">💊 Obat & P3K</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Stok &amp; Satuan</label>
                <div class="flex gap-2">
                    <input type="number" name="stok" min="0" value="1" required class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-red-500">
                    <input type="text" name="satuan" value="Pcs" required class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-red-500">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Deskripsi Kebutuhan</label>
            <textarea name="deskripsi" rows="3" required placeholder="Jelaskan kebutuhan barang ini..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-red-500"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('barangs.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition shadow-md shadow-red-600/30">
                Simpan ke Database
            </button>
        </div>
    </form>

</div>
@endsection
