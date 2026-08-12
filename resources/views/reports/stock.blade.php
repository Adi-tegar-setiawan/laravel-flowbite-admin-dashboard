@extends('layouts.dashboard')

@section('content')
<div class="p-6">

    {{-- KOP LAPORAN (HANYA MUNCUL SAAT PRINT) --}}
    <div class="hidden print:block mb-6 border-b-2 border-gray-800 pb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold uppercase text-black">
                    {{ $appSettings['company_name'] ?? $appSettings['app_name'] ?? 'STOCKIFY WAREHOUSE' }}
                </h1>
                <p class="text-xs text-gray-600">Sistem Manajemen Stok & Inventaris Gudang</p>
                @if(!empty($appSettings['company_email']))
                    <p class="text-xs text-gray-500">Email: {{ $appSettings['company_email'] }} | Telp: {{ $appSettings['company_phone'] ?? '-' }}</p>
                @endif
            </div>
            <div class="text-right text-xs text-gray-600">
                <p><span class="font-semibold">Laporan:</span> Stok Barang</p>
                <p><span class="font-semibold">Tanggal Dicetak:</span> {{ now()->translatedFormat('d F Y H:i') }}</p>
                <p><span class="font-semibold">Dicetak Oleh:</span> {{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    {{-- HEADER (TAMPILAN WEB) --}}
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Laporan Stok Barang
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Rekapitulasi kondisi stok barang seluruh produk di gudang.
            </p>
        </div>

        <button 
            onclick="window.print()" 
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 shrink-0"
        >
            🖨 Print Laporan
        </button>
    </div>

    {{-- FILTER FORM (HIDDEN SAAT PRINT) --}}
    <div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 print:hidden">
        <form method="GET" action="{{ route('reports.stock') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            {{-- Filter Kategori --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Mulai --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Filter Tanggal Selesai --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Tombol Submit Filter --}}
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    Filter Laporan
                </button>
            </div>

        </form>
    </div>

    {{-- TABEL LAPORAN STOK DENGAN SCROLL INTERNAL --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden print:border-none print:shadow-none">
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar print:max-h-none print:overflow-visible">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 print:text-black print:w-full">
                <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 print:bg-gray-200 print:text-black print:static">
                    <tr class="print:border-b-2 print:border-black">
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">No</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">SKU</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-center print:px-2 print:py-1">Stok Min.</th>
                        <th scope="col" class="px-6 py-3 text-center print:px-2 print:py-1">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3 text-center print:px-2 print:py-1">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 print:divide-gray-300">
                    @forelse ($products as $index => $product)
                        @php
                            $currentStock = $product->current_stock ?? $product->stock ?? 0;
                            $minStock = $product->minimum_stock ?? $product->min_stock ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 print:border-b print:border-gray-300 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs print:text-black print:px-2 print:py-2">
                                {{ $product->sku }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 print:text-black print:px-2 print:py-2">
                                {{ $product->category?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center print:text-black print:px-2 print:py-2">
                                {{ $minStock }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $currentStock }}
                            </td>
                            <td class="px-6 py-4 text-center print:px-2 print:py-2">
                                @if ($currentStock <= 0)
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900/30 dark:text-red-300 print:bg-transparent print:text-red-600 print:font-bold">
                                        Habis
                                    </span>
                                @elseif ($currentStock <= $minStock)
                                    <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-amber-900/30 dark:text-amber-300 print:bg-transparent print:text-yellow-600 print:font-bold">
                                        Stok Rendah
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900/30 dark:text-green-300 print:bg-transparent print:text-green-600 print:font-bold">
                                        Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 print:text-black">
                                Tidak ada data stok produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TANDA TANGAN (HANYA MUNCUL SAAT PRINT) --}}
    <div class="hidden print:grid grid-cols-2 gap-4 mt-12 text-xs text-center text-black">
        <div>
            <p>Dibuat Oleh,</p>
            <div class="h-16"></div>
            <p class="font-semibold underline">{{ auth()->user()->name }}</p>
            <p class="text-gray-500">Staff / Admin Gudang</p>
        </div>
        <div>
            <p>Mengetahui,</p>
            <div class="h-16"></div>
            <p class="font-semibold underline">( .................................... )</p>
            <p class="text-gray-500">Manajer Gudang</p>
        </div>
    </div>

</div>

{{-- CSS KHUSUS PRINT CETAK PRO --}}
<style>
@media print {
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    aside, header, footer, .sidebar, [class*="sidebar"] {
        display: none !important;
    }
    main {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endsection