@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Produk
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tambahkan produk baru ke dalam sistem stok.
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

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Kategori
                        </label>

                        <select id="category_id" name="category_id" required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label for="supplier_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Supplier
                        </label>

                        <select id="supplier_id" name="supplier_id" required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nama Produk
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="255"
                            placeholder="Contoh: Laptop ASUS"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label for="sku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            SKU
                        </label>

                        <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required maxlength="255"
                            placeholder="Contoh: LPT-ASUS-001"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Purchase Price --}}
                    <div>
                        <label for="purchase_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Harga Beli
                        </label>

                        <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required min="0" step="0.01"
                            placeholder="Contoh: 5000000"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Selling Price --}}
                    <div>
                        <label for="selling_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Harga Jual
                        </label>

                        <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required min="0" step="0.01"
                            placeholder="Contoh: 5500000"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Initial Stock (Stok Awal) --}}
                    <div>
                        <label for="initial_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Stok Awal
                        </label>

                        <input type="number" id="initial_stock" name="initial_stock" value="{{ old('initial_stock', 0) }}" required min="0" step="1"
                            placeholder="Contoh: 10"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Minimum Stock --}}
                    <div>
                        <label for="minimum_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Minimum Stok
                        </label>

                        <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" required min="0" step="1"
                            placeholder="Contoh: 5"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="4" placeholder="Deskripsi produk..."
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
                    </div>

                    {{-- Image --}}
                    <div class="md:col-span-2">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Gambar Produk
                        </label>

                        <input type="file" id="image" name="image" accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600">

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Format gambar yang diperbolehkan.
                        </p>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 mt-6">
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Simpan Produk
                    </button>

                    <a href="{{ route('products.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
@endsection