@extends('layouts.app')

@section('title', 'Edit Data Barang - StokKos Laravel')

@section('content')
<div class="max-w-xl mx-auto bg-slate-950 p-6 sm:p-8 rounded-3xl border border-slate-800 space-y-6 shadow-xl">
    
    <div class="border-b border-slate-800 pb-4">
        <h2 class="text-xl font-black text-white">Edit Data Barang (Update)</h2>
        <p class="text-xs text-slate-400 mt-1">Memperbarui record di database via <code class="text-blue-400 font-mono">$barang->update()</code></p>
    </div>

    <form action="{{ route('barangs.update', $barang->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Nama Barang</label>
            <input type="text" name="nama" value="{{ $barang->nama }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-blue-500">
                    <option value="Makanan" {{ $barang->kategori == 'Makanan' ? 'selected' : '' }}>🍜 Makanan & Minuman</option>
                    <option value="Mandi" {{ $barang->kategori == 'Mandi' ? 'selected' : '' }}>🧼 Mandi & Cuci</option>
                    <option value="Kebersihan" {{ $barang->kategori == 'Kebersihan' ? 'selected' : '' }}>🧹 Kebersihan</option>
                    <option value="P3K" {{ $barang->kategori == 'P3K' ? 'selected' : '' }}>💊 Obat & P3K</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Stok &amp; Satuan</label>
                <div class="flex gap-2">
                    <input type="number" name="stok" min="0" value="{{ $barang->stok }}" required class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-blue-500">
                    <input type="text" name="satuan" value="{{ $barang->satuan }}" required class="w-1/2 px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-mono font-bold text-slate-400 uppercase mb-1">Deskripsi Kebutuhan</label>
            <textarea name="deskripsi" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-sm text-white focus:outline-none focus:border-blue-500">{{ $barang->deskripsi }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
            <a href="{{ route('barangs.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-md shadow-blue-600/30">
                Perbarui Data (Update)
            </button>
        </div>
    </form>

</div>
@endsection
