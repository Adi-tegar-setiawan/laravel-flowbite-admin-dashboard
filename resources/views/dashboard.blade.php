@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Dashboard Stockify
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Selamat datang, {{ auth()->user()->name }} ({{ auth()->user()->role }}).
                @if(auth()->user()->role === 'Staff Gudang')
                    Berikut daftar tugas penerimaan dan pengeluaran barang yang harus diselesaikan.
                @elseif(auth()->user()->role === 'Manajer Gudang')
                    Berikut ringkasan informasi stok barang gudang saat ini.
                @else
                    Berikut ringkasan statistik dan aktivitas sistem terbaru.
                @endif
            </p>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-green-900/30 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- 1. DASHBOARD STAFF GUDANG (MURNI DAFTAR TUGAS OPERASIONAL & KONFIRMASI)   --}}
    {{-- ========================================================================= --}}
    @if(auth()->user()->role === 'Staff Gudang')
        
        <div class="grid grid-cols-1 gap-6 mb-6 xl:grid-cols-2">

            {{-- Barang Masuk yang Perlu Diperiksa --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                    📥 Barang Masuk (Perlu Diperiksa)
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Daftar penerimaan barang yang memerlukan verifikasi fisik di lapangan.
                </p>

                @if(isset($dashboard['pendingStockIn']) && $dashboard['pendingStockIn']->isNotEmpty())
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[380px] overflow-y-auto custom-scrollbar pr-1">
                        @foreach($dashboard['pendingStockIn'] as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $item->product?->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Jumlah: <span class="font-bold text-green-600">+{{ $item->quantity }}</span> | Supplier: {{ $item->product?->supplier?->name ?? '-' }}
                                    </p>
                                </div>

                                <form action="{{ route('transactions.update-status', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Diterima">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-300">
                                        Konfirmasi Terima
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-xs text-gray-500 bg-gray-50 rounded-lg dark:bg-gray-700/50 dark:text-gray-400">
                        Tidak ada barang masuk yang perlu diperiksa saat ini.
                    </div>
                @endif
            </div>

            {{-- Barang Keluar yang Perlu Disiapkan --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                    📤 Barang Keluar (Perlu Disiapkan)
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Daftar pengeluaran barang yang perlu disiapkan di area transit/shipping.
                </p>

                @if(isset($dashboard['pendingStockOut']) && $dashboard['pendingStockOut']->isNotEmpty())
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[380px] overflow-y-auto custom-scrollbar pr-1">
                        @foreach($dashboard['pendingStockOut'] as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white">
                                        {{ $item->product?->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Jumlah: <span class="font-bold text-red-600">-{{ $item->quantity }}</span>
                                    </p>
                                </div>

                                <form action="{{ route('transactions.update-status', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Dikeluarkan">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
                                        Konfirmasi Keluar
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-xs text-gray-500 bg-gray-50 rounded-lg dark:bg-gray-700/50 dark:text-gray-400">
                        Tidak ada barang keluar yang perlu disiapkan saat ini.
                    </div>
                @endif
            </div>

        </div>

    {{-- ========================================================================= --}}
    {{-- 2. DASHBOARD MANAJER GUDANG & ADMIN                                       --}}
    {{-- ========================================================================= --}}
    @else

        {{-- METRIK RINGKASAN --}}
        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 {{ auth()->user()->role === 'Admin' ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }}">

            {{-- Total Produk (Khusus Admin) --}}
            @if(auth()->user()->role === 'Admin')
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                            <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $dashboard['totalProducts'] ?? 0 }}
                            </h2>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900/40 dark:text-blue-400">📦</div>
                    </div>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline">Lihat produk →</a>
                </div>
            @endif

            {{-- Barang Masuk Hari Ini --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Masuk Hari Ini</p>
                        <h2 class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ $dashboard['stockInToday'] ?? 0 }}
                        </h2>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:bg-green-900/40 dark:text-green-400">↑</div>
                </div>
                <a href="{{ route('transactions.index') }}" class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline">Lihat transaksi →</a>
            </div>

            {{-- Barang Keluar Hari Ini --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Barang Keluar Hari Ini</p>
                        <h2 class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ $dashboard['stockOutToday'] ?? 0 }}
                        </h2>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-100 rounded-lg dark:bg-red-900/40 dark:text-red-400">↓</div>
                </div>
                <a href="{{ route('transactions.index') }}" class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline">Lihat transaksi →</a>
            </div>

            {{-- Stok Menipis / Rendah --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Menipis</p>
                        <h2 class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">
                            {{ isset($dashboard['lowStockProducts']) ? $dashboard['lowStockProducts']->count() : 0 }}
                        </h2>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 text-amber-600 bg-amber-100 rounded-lg dark:bg-amber-900/40 dark:text-amber-400">⚠</div>
                </div>
                <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline">Periksa stok →</a>
            </div>

        </div>

        {{-- GRAFIK STOK & TRANSAKSI (KHUSUS ADMIN) --}}
        @if(auth()->user()->role === 'Admin')
            <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Statistik Transaksi
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Barang masuk dan keluar selama 7 hari terakhir.
                        </p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Semua transaksi</a>
                </div>

                <div class="relative h-80">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        @endif

        {{-- LOWER SECTION --}}
        <div class="grid grid-cols-1 gap-6 {{ auth()->user()->role === 'Admin' ? 'xl:grid-cols-2' : 'grid-cols-1' }}">

            {{-- WIDGET STOK MENIPIS (Manajer Gudang & Admin) --}}
            <div class="flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Produk Stok Rendah
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Produk yang berada pada atau di bawah batas minimum.
                            </p>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat semua</a>
                    </div>
                </div>

                @if (isset($dashboard['lowStockProducts']) && $dashboard['lowStockProducts']->isNotEmpty())
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[380px] overflow-y-auto custom-scrollbar">
                        {{-- Menampilkan seluruh daftar produk stok rendah tanpa di-take(5) agar bisa discroll --}}
                        @foreach ($dashboard['lowStockProducts'] as $product)
                            @php
                                $currentStock = $product->current_stock ?? $product->stock ?? 0;
                                $minStock = $product->minimum_stock ?? $product->min_stock ?? 0;
                            @endphp
                            <div class="flex items-center justify-between p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold {{ $currentStock <= 0 ? 'text-red-600' : 'text-amber-600' }}">
                                        {{ $currentStock }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Minimum: {{ $minStock }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6">
                        <div class="p-4 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                            Semua produk memiliki stok yang aman.
                        </div>
                    </div>
                @endif
            </div>

            {{-- AKTIVITAS PENGGUNA TERBARU (KHUSUS ADMIN) --}}
            @if(auth()->user()->role === 'Admin')
                <div class="flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Aktivitas Pengguna Terbaru
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Log riwayat aktivitas pengguna di sistem.
                                </p>
                            </div>
                            <a href="{{ route('activity-logs.index') }}" class="text-sm font-medium text-blue-600 hover:underline shrink-0">Lihat semua</a>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[380px] overflow-y-auto custom-scrollbar">
                        @forelse ($dashboard['recentActivities'] ?? [] as $activity)
                            <div class="flex items-start gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-400 text-xs">
                                    📝
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-semibold">{{ $activity->user?->name ?? 'System' }}</span>
                                        — {{ $activity->description ?? $activity->action }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-sm text-gray-500 dark:text-gray-400">Belum ada log aktivitas pengguna.</div>
                        @endforelse
                    </div>
                </div>
            @endif

        </div>

    @endif

</div>

{{-- SCRIPT CHART (KHUSUS ADMIN) --}}
@if(auth()->user()->role === 'Admin')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($dashboard['transactionChart'] ?? []);
        const ctx = document.getElementById('transactionChart');

        if (ctx && chartData.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.date),
                    datasets: [
                        {
                            label: 'Barang Masuk',
                            data: chartData.map(item => item.stockIn),
                            borderColor: '#10B981',
                            backgroundColor: '#10B981',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Barang Keluar',
                            data: chartData.map(item => item.stockOut),
                            borderColor: '#EF4444',
                            backgroundColor: '#EF4444',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        }
    </script>
@endif

@endsection