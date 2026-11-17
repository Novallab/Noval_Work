<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StokKos.id - Laravel CRUD')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col antialiased">

    <!-- Header Navbar -->
    <header class="bg-slate-950 border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('barangs.index') }}" class="flex items-center gap-2.5 font-bold text-white text-lg">
                <span class="p-1.5 bg-red-600/20 text-red-500 rounded-lg border border-red-500/30">📦</span>
                <span>StokKos<span class="text-red-500">.id</span></span>
                <span class="text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-red-500/20 text-red-400 border border-red-500/30 ml-2">
                    Laravel CRUD &bull; Week 9
                </span>
            </a>

            <nav class="flex items-center gap-3">
                <a href="{{ route('barangs.index') }}" class="text-xs font-semibold px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                    Daftar Barang
                </a>
                <a href="{{ route('barangs.create') }}" class="text-xs font-semibold px-3.5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white transition shadow-sm">
                    + Tambah Barang
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500 text-emerald-300 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <span class="text-xs font-mono font-bold">SUCCESS</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-800 py-6 text-center text-xs text-slate-500 mt-auto">
        <p>&copy; 2026 StokKos.id &bull; Web Programming Pertemuan 9 (Laravel Framework &bull; Database &bull; Eloquent ORM)</p>
    </footer>

</body>
</html>
