@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Products
            </h1>

            <a href="{{ route('products.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                + Tambah Produk
            </a>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('success') }}
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
                    <p class="text-gray-500">
                        Belum ada produk.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection