@extends('layouts.dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
        Edit Produk
    </h1>

    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Perbarui informasi produk.
    </p>
</div>

@if ($errors->any())
    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">

    <form
        action="{{ route('products.update', $product->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Category --}}
            <div>
                <label for="category_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Kategori
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <option value="">Pilih kategori</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Supplier --}}
            <div>
                <label for="supplier_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Supplier
                </label>

                <select
                    id="supplier_id"
                    name="supplier_id"
                    required
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                    <option value="">Pilih supplier</option>

                    @foreach ($suppliers as $supplier)
                        <option
                            value="{{ $supplier->id }}"
                            {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Name --}}
            <div>
                <label for="name"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Produk
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    required
                    maxlength="255"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- SKU --}}
            <div>
                <label for="sku"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    SKU
                </label>

                <input
                    type="text"
                    id="sku"
                    name="sku"
                    value="{{ old('sku', $product->sku) }}"
                    required
                    maxlength="255"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Purchase Price --}}
            <div>
                <label for="purchase_price"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Harga Beli
                </label>

                <input
                    type="number"
                    id="purchase_price"
                    name="purchase_price"
                    value="{{ old('purchase_price', $product->purchase_price) }}"
                    required
                    min="0"
                    step="0.01"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Selling Price --}}
            <div>
                <label for="selling_price"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Harga Jual
                </label>

                <input
                    type="number"
                    id="selling_price"
                    name="selling_price"
                    value="{{ old('selling_price', $product->selling_price) }}"
                    required
                    min="0"
                    step="0.01"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Minimum Stock --}}
            <div>
                <label for="minimum_stock"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Minimum Stok
                </label>

                <input
                    type="number"
                    id="minimum_stock"
                    name="minimum_stock"
                    value="{{ old('minimum_stock', $product->minimum_stock) }}"
                    required
                    min="0"
                    step="1"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label for="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Image --}}
            <div class="md:col-span-2">
                <label for="image"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Gambar Produk
                </label>

                @if ($product->image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                    </div>
                @endif

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Kosongkan jika tidak ingin mengganti gambar.
                </p>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 mt-6">

            <button
                type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Simpan Perubahan
            </button>

            <a
                href="{{ route('products.index') }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection