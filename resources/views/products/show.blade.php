@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Detail Produk
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Informasi rinci mengenai produk {{ $product->name }}.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('products.edit', $product->id) }}"
               class="px-4 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300">
                Edit Produk
            </a>
            <a href="{{ route('products.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">
                Kembali
            </a>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Gambar Produk --}}
            <div class="md:col-span-1">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Gambar Produk
                </label>
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-64 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                @else
                    <div class="flex items-center justify-center w-full h-64 bg-gray-100 rounded-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-700 text-gray-400">
                        Tidak ada gambar
                    </div>
                @endif
            </div>

            {{-- Informasi Rinci --}}
            <div class="md:col-span-2 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Nama Produk</span>
                        <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">SKU</span>
                        <span class="text-lg font-medium text-gray-900 dark:text-white">{{ $product->sku }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Kategori</span>
                        <span class="text-base text-gray-900 dark:text-white">{{ $product->category?->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Supplier</span>
                        <span class="text-base text-gray-900 dark:text-white">{{ $product->supplier?->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Harga Beli</span>
                        <span class="text-base font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Harga Jual</span>
                        <span class="text-base font-semibold text-green-600 dark:text-green-400">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase">Batas Stok Minimum</span>
                        <span class="text-base text-gray-900 dark:text-white">{{ $product->minimum_stock }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t dark:border-gray-700">
                    <span class="block text-xs font-semibold text-gray-400 uppercase mb-1">Deskripsi</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                        {{ $product->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection