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

    {{-- Product Attributes --}}
<div class="p-6 mt-6 bg-white rounded-lg shadow dark:bg-gray-800">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            Atribut Produk
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Tambahkan informasi tambahan untuk produk ini.
        </p>
    </div>

    {{-- Form Tambah Attribute --}}
    <form
        action="{{ route('products.attributes.store', $product->id) }}"
        method="POST"
        class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3"
    >
        @csrf

        {{-- Nama Attribute --}}
        <div>
            <label
                for="attribute_name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
                Nama Atribut
            </label>

            <input
                type="text"
                id="attribute_name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Warna"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >

            @error('name')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Value --}}
        <div>
            <label
                for="attribute_value"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
                Nilai
            </label>

            <input
                type="text"
                id="attribute_value"
                name="value"
                value="{{ old('value') }}"
                placeholder="Contoh: Hitam"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                       focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required
            >

            @error('value')
                <p class="mt-1 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex items-end">
            <button
                type="submit"
                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600
                       rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
            >
                Tambah Atribut
            </button>
        </div>

    </form>

        {{-- Daftar Attribute --}}
        @if ($product->attributes->isNotEmpty())

            <div class="overflow-x-auto">

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">
                                Nama Atribut
                            </th>

                            <th class="px-6 py-3">
                                Nilai
                            </th>

                            <th class="px-6 py-3 text-right">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($product->attributes as $attribute)

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $attribute->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $attribute->value }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    <div class="flex justify-end items-center gap-3">

                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'products.attributes.edit',
                                                [
                                                    'productId' => $product->id,
                                                    'attributeId' => $attribute->id,
                                                ]
                                            ) }}"
                                            class="font-medium text-blue-600 hover:underline"
                                        >
                                            Edit
                                        </a>

                                        {{-- Hapus --}}
                                        <form
                                            action="{{ route(
                                                'products.attributes.destroy',
                                                [
                                                    'productId' => $product->id,
                                                    'attributeId' => $attribute->id,
                                                ]
                                            ) }}"
                                            method="POST"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="font-medium text-red-600 hover:underline"
                                                onclick="return confirm('Hapus atribut ini?')"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="p-4 text-sm text-gray-500 bg-gray-50 rounded-lg dark:bg-gray-700 dark:text-gray-400">
                Belum ada atribut untuk produk ini.
            </div>

        @endif

    </div>
</div>
@endsection