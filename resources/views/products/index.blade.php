@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Aksi --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Products
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kelola daftar stok barang dan informasi produk gudang.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- TOMBOL TAMBAH PRODUK (Muncul untuk Admin & Manajer Gudang) --}}
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('products.create') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                        + Tambah Produk
                    </a>
                @endif

                {{-- TOMBOL IMPORT & EXPORT (KHUSUS ADMIN) --}}
                @if(auth()->user()->role === 'Admin')
                    {{-- Tombol Trigger Modal Import --}}
                    <button type="button" onclick="toggleImportModal(true)"
                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        📥 Import Produk
                    </button>

                    {{-- Tombol Export --}}
                    <a href="{{ route('products.export') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        📤 Export Produk
                    </a>
                @endif
            </div>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        {{-- Flash Message Error --}}
        @if (session('error'))
            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
                <span class="font-medium">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="p-4 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                        🔍
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama produk atau SKU..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shrink-0">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 shrink-0 transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table Container dengan Scroll Internal (Height 500px) --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            
            @if ($products->count())
                {{-- Area Tabel Ber-Scroll Internal --}}
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Nama Produk & SKU</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Supplier</th>
                                <th scope="col" class="px-6 py-3 text-center">Stok Saat Ini</th>
                                <th scope="col" class="px-6 py-3 text-center">Stok Min.</th>
                                <th scope="col" class="px-6 py-3">Harga Jual</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                                <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($products as $index => $product)
                                @php
                                    $currentStock = $product->currentStock ?? $product->current_stock ?? $product->stock ?? 0;
                                    $minStock = $product->minimum_stock ?? $product->min_stock ?? 0;
                                @endphp
                                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">
                                    {{-- Nomor --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $products->firstItem() + $index }}
                                    </td>

                                    {{-- Nama Produk & SKU --}}
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-xs font-mono text-gray-400">
                                            SKU: {{ $product->sku }}
                                        </div>
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="px-6 py-4">
                                        {{ $product->category?->name ?? '-' }}
                                    </td>

                                    {{-- Supplier --}}
                                    <td class="px-6 py-4">
                                        {{ $product->supplier?->name ?? '-' }}
                                    </td>

                                    {{-- Stok Saat Ini --}}
                                    <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">
                                        {{ $currentStock }}
                                    </td>

                                    {{-- Stok Minimum --}}
                                    <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ $minStock }}
                                    </td>

                                    {{-- Harga Jual --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </td>

                                    {{-- Status Stok --}}
                                    <td class="px-6 py-4 text-center">
                                        @if ($currentStock <= 0)
                                            <span class="px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900/40 dark:text-red-300">
                                                Habis
                                            </span>
                                        @elseif ($currentStock <= $minStock)
                                            <span class="px-2.5 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full dark:bg-amber-900/40 dark:text-amber-300">
                                                Stok Rendah
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/40 dark:text-green-300">
                                                Aman
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tombol Aksi --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Detail (Admin & Manajer Gudang) --}}
                                            <a href="{{ route('products.show', $product->id) }}"
                                               class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                Detail
                                            </a>

                                            {{-- Edit & Hapus (HANYA ADMIN) --}}
                                            @if(auth()->user()->role === 'Admin')
                                                <a href="{{ route('products.edit', $product->id) }}"
                                                   class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 transition-colors">
                                                    Edit
                                                </a>

                                                <form action="{{ route('products.destroy', $product->id) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-colors">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Belum ada data produk yang terdaftar.
                </div>
            @endif

        </div>

    </div>

    {{-- MODAL IMPORT DATA PRODUK (KHUSUS ADMIN) --}}
    @if(auth()->user()->role === 'Admin')
        <div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="relative w-full max-w-md p-4">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                    
                    {{-- Header Modal --}}
                    <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Import Data Produk
                        </h3>
                        <button type="button" onclick="toggleImportModal(false)" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-700 dark:hover:text-white">
                            ✕
                        </button>
                    </div>
                    
                    {{-- Form Import --}}
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Pilih File Excel / CSV (.xlsx, .csv)
                            </label>
                            <input type="file" name="file" accept=".csv, .xlsx, .xls" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" onclick="toggleImportModal(false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Proses Import
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <script>
            function toggleImportModal(show) {
                const modal = document.getElementById('importModal');
                if (show) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }
        </script>
    @endif
@endsection