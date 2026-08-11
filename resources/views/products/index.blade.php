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
                    Kelola daftar stok barang dan informasi produk.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- TOMBOL TAMBAH PRODUK (Muncul untuk Admin & Manajer Gudang) --}}
                @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                    <a href="{{ route('products.create') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        + Tambah Produk
                    </a>
                @endif

                {{-- TOMBOL IMPORT & EXPORT (KHUSUS ADMIN) --}}
                @if(auth()->user()->role === 'Admin')
                    {{-- Tombol Trigger Modal Import --}}
                    <button type="button" onclick="toggleImportModal(true)"
                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        📥 Import Produk
                    </button>

                    {{-- Tombol Export --}}
                    <a href="{{ route('products.export') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        📤 Export Produk
                    </a>
                @endif
            </div>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash Message Error --}}
        @if (session('error'))
            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="mb-6">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nama atau SKU..."
                       class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <button type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-700">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($products->count())
                    <div class="space-y-3">
                        @foreach ($products as $product)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border rounded-lg dark:border-gray-700 gap-4">

                                {{-- Informasi Produk --}}
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        SKU: {{ $product->sku }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        Kategori:
                                        {{ $product->category?->name ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        Supplier:
                                        {{ $product->supplier?->name ?? '-' }}
                                    </div>

                                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Stok Saat Ini:
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->currentStock }}
                                        </span>
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Stok Minimum:
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $product->minimum_stock }}
                                        </span>
                                    </div>

                                    <div class="mt-2">
                                        @if ($product->currentStock <= $product->minimum_stock)
                                            <span class="px-2.5 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
                                                Stok Rendah
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                                                Stok Aman
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Tombol Aksi (Detail, Edit, Delete) --}}
                                <div class="flex items-center gap-2">
                                    {{-- Detail (Admin & Manajer Gudang) --}}
                                    <a href="{{ route('products.show', $product->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                        Detail
                                    </a>

                                    {{-- Edit & Hapus (HANYA ADMIN) --}}
                                    @if(auth()->user()->role === 'Admin')
                                        <a href="{{ route('products.edit', $product->id) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">
                        Belum ada produk.
                    </p>
                @endif

            </div>
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