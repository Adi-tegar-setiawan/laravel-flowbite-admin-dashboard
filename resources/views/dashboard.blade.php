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
                Selamat datang, {{ auth()->user()->name }}.
                Berikut ringkasan kondisi gudang saat ini.
            </p>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ now()->translatedFormat('d F Y') }}
        </div>

    </div>


    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL PRODUCTS --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Total Produk
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $dashboard['totalProducts'] }}
                    </h2>

                </div>

                <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:bg-blue-900/40 dark:text-blue-400">
                    📦
                </div>

            </div>

            <a
                href="{{ route('products.index') }}"
                class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline"
            >
                Lihat produk →
            </a>

        </div>


        {{-- STOCK IN --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Barang Masuk Hari Ini
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ $dashboard['stockInToday'] }}
                    </h2>

                </div>

                <div class="flex items-center justify-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:bg-green-900/40 dark:text-green-400">
                    ↑
                </div>

            </div>

            <a
                href="{{ route('transactions.index') }}"
                class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline"
            >
                Lihat transaksi →
            </a>

        </div>


        {{-- STOCK OUT --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Barang Keluar Hari Ini
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                        {{ $dashboard['stockOutToday'] }}
                    </h2>

                </div>

                <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-100 rounded-lg dark:bg-red-900/40 dark:text-red-400">
                    ↓
                </div>

            </div>

            <a
                href="{{ route('transactions.index') }}"
                class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline"
            >
                Lihat transaksi →
            </a>

        </div>


        {{-- LOW STOCK --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Stok Rendah
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">
                        {{ $dashboard['lowStockProducts']->count() }}
                    </h2>

                </div>

                <div class="flex items-center justify-center w-12 h-12 text-amber-600 bg-amber-100 rounded-lg dark:bg-amber-900/40 dark:text-amber-400">
                    ⚠
                </div>

            </div>

            <a
                href="{{ route('products.index') }}"
                class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline"
            >
                Periksa stok →
            </a>

        </div>

    </div>


    {{-- CHART --}}
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

            <a
                href="{{ route('transactions.index') }}"
                class="text-sm font-medium text-blue-600 hover:underline"
            >
                Semua transaksi
            </a>

        </div>


        <div class="relative h-80">

            <canvas id="transactionChart"></canvas>

        </div>

    </div>


    {{-- LOWER SECTION --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- LOW STOCK --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

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

                    <a
                        href="{{ route('products.index') }}"
                        class="text-sm font-medium text-blue-600 hover:underline"
                    >
                        Lihat semua
                    </a>

                </div>

            </div>


            @if ($dashboard['lowStockProducts']->isNotEmpty())

                <div class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach ($dashboard['lowStockProducts']->take(5) as $product)

                        @php
                            // Menggunakan atribut stok saat ini dari model/repository atau fallback
                            $currentStock = $product->current_stock ?? $product->stock ?? 0;
                            $minStock = $product->minimum_stock ?? $product->min_stock ?? 0;
                        @endphp

                        <div class="flex items-center justify-between p-5">

                            <div>

                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    SKU: {{ $product->sku }}
                                </p>

                            </div>


                            <div class="text-right">

                                <p class="font-bold
                                    {{ $currentStock <= 0
                                        ? 'text-red-600'
                                        : 'text-amber-600' }}">

                                    {{ $currentStock }}

                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Minimum: {{ $minStock }}
                                </p>

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


        {{-- RECENT ACTIVITY (ActivityLog) --}}
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

                    {{-- Link ke Halaman Activity Log Lengkap --}}
                    <a
                        href="{{ route('activity-logs.index') }}"
                        class="text-sm font-medium text-blue-600 hover:underline shrink-0"
                    >
                        Lihat semua
                    </a>

                </div>

            </div>


            {{-- Diberikan max-height dan overflow-y-auto agar tidak scroll memanjang ke bawah --}}
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[380px] overflow-y-auto custom-scrollbar">

                @forelse ($dashboard['recentActivities'] as $activity)

                    <div class="flex items-start gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                        <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-400 text-xs">
                            📝
                        </div>


                        <div class="flex-1 min-w-0">

                            <p class="text-sm text-gray-900 dark:text-white">

                                <span class="font-semibold">
                                    {{ $activity->user?->name ?? 'System' }}
                                </span>

                                — {{ $activity->description ?? $activity->action }}

                            </p>


                            <p class="mt-1 text-xs text-gray-400">

                                {{ $activity->created_at->diffForHumans() }}

                            </p>

                        </div>

                    </div>

                @empty

                    <div class="p-6 text-sm text-gray-500 dark:text-gray-400">

                        Belum ada log aktivitas pengguna.

                    </div>

                @endforelse

            </div>

        </div>


{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const chartData = @json($dashboard['transactionChart']);

    const ctx = document.getElementById('transactionChart');

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

            plugins: {

                legend: {
                    display: true
                }

            },

            scales: {

                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }

                }

            }

        }

    });

</script>

@endsection