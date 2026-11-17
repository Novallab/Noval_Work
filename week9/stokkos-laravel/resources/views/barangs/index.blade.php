@extends('layouts.app')

@section('title', 'Daftar Barang - StokKos Laravel')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-2xl font-black text-white">Data Inventaris Kos (Sprint 07)</h2>
            <p class="text-xs text-slate-400 mt-1">Data ditampilkan secara dinamis dari database via <code class="text-red-400 font-mono">Barang::all()</code></p>
        </div>
        <a href="{{ route('barangs.create') }}" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold transition shadow-md shadow-red-600/30 flex items-center gap-1.5 w-fit">
            <span>+</span> <span>Tambah Barang Baru</span>
        </a>
    </div>

    <!-- Tabel Data (Slide 12: Read) -->
    <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950">
        <table class="w-full text-left text-sm text-slate-300">
            <thead class="bg-slate-900 text-xs uppercase font-mono tracking-wider text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="py-3.5 px-4 text-center w-16">No</th>
                    <th class="py-3.5 px-4">Nama Barang</th>
                    <th class="py-3.5 px-4">Kategori</th>
                    <th class="py-3.5 px-4 text-center">Stok</th>
                    <th class="py-3.5 px-6">Deskripsi Kebutuhan</th>
                    <th class="py-3.5 px-4 text-center w-40">Aksi (CRUD)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @forelse($barangs as $index => $b)
                <tr class="hover:bg-slate-800/30 transition">
                    <td class="py-4 px-4 text-center font-mono text-xs text-slate-500">{{ $index + 1 }}</td>
                    <td class="py-4 px-4 font-bold text-white">{{ $b->nama }}</td>
                    <td class="py-4 px-4">
                        <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-300 text-xs border border-slate-700">{{ $b->kategori }}</span>
                    </td>
                    <td class="py-4 px-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-mono bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            {{ $b->stok }} {{ $b->satuan }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-slate-400 text-xs leading-relaxed">{{ $b->deskripsi }}</td>
                    <td class="py-4 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('barangs.edit', $b->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-600/20 hover:bg-blue-600 text-blue-300 hover:text-white border border-blue-500/30 text-xs font-semibold transition">
                                Edit
                            </a>
                            <form action="{{ route('barangs.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/30 text-xs font-semibold transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500">
                        Belum ada data barang di database. Silakan klik tombol <strong>+ Tambah Barang Baru</strong>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
