@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Tambah Stock Opname
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Catat hasil pemeriksaan stok fisik produk.
        </p>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg
                    bg-red-50 dark:bg-gray-800 dark:text-red-400">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">

        <form action="{{ route('stock-opnames.store') }}"
              method="POST">

            @csrf


            {{-- Product --}}
            <div class="mb-6">

                <label for="product_id"
                       class="block mb-2 text-sm font-medium
                              text-gray-900 dark:text-white">

                    Produk

                </label>

                <select id="product_id"
                        name="product_id"
                        required
                        class="bg-gray-50 border border-gray-300
                               text-gray-900 text-sm rounded-lg
                               focus:ring-blue-500 focus:border-blue-500
                               block w-full p-2.5
                               dark:bg-gray-700 dark:border-gray-600
                               dark:text-white">

                    <option value="">
                        -- Pilih Produk --
                    </option>

                    @foreach ($products as $product)

                        <option value="{{ $product->id }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}>

                            {{ $product->name }}
                            - SKU: {{ $product->sku }}

                        </option>

                    @endforeach

                </select>

                @error('product_id')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Physical Stock --}}
            <div class="mb-6">

                <label for="physical_stock"
                       class="block mb-2 text-sm font-medium
                              text-gray-900 dark:text-white">

                    Stok Fisik

                </label>

                <input type="number"
                       id="physical_stock"
                       name="physical_stock"
                       value="{{ old('physical_stock') }}"
                       min="0"
                       required
                       placeholder="Masukkan jumlah stok fisik"
                       class="bg-gray-50 border border-gray-300
                              text-gray-900 text-sm rounded-lg
                              focus:ring-blue-500 focus:border-blue-500
                              block w-full p-2.5
                              dark:bg-gray-700 dark:border-gray-600
                              dark:text-white">

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Masukkan jumlah barang yang benar-benar ditemukan
                    saat pemeriksaan fisik.
                </p>

                @error('physical_stock')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Date --}}
            <div class="mb-6">

                <label for="date"
                       class="block mb-2 text-sm font-medium
                              text-gray-900 dark:text-white">

                    Tanggal Opname

                </label>

                <input type="date"
                       id="date"
                       name="date"
                       value="{{ old('date', now()->format('Y-m-d')) }}"
                       required
                       class="bg-gray-50 border border-gray-300
                              text-gray-900 text-sm rounded-lg
                              focus:ring-blue-500 focus:border-blue-500
                              block w-full p-2.5
                              dark:bg-gray-700 dark:border-gray-600
                              dark:text-white">

                @error('date')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Notes --}}
            <div class="mb-6">

                <label for="notes"
                       class="block mb-2 text-sm font-medium
                              text-gray-900 dark:text-white">

                    Catatan

                </label>

                <textarea id="notes"
                          name="notes"
                          rows="4"
                          placeholder="Contoh: Ditemukan 2 barang rusak."
                          class="bg-gray-50 border border-gray-300
                                 text-gray-900 text-sm rounded-lg
                                 focus:ring-blue-500 focus:border-blue-500
                                 block w-full p-2.5
                                 dark:bg-gray-700 dark:border-gray-600
                                 dark:text-white">{{ old('notes') }}</textarea>

                @error('notes')

                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Actions --}}
            <div class="flex items-center gap-3">

                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium
                               text-white bg-blue-600 rounded-lg
                               hover:bg-blue-700
                               focus:ring-4 focus:ring-blue-300">

                    Simpan Stock Opname

                </button>

                <a href="{{ route('stock-opnames.index') }}"
                   class="px-5 py-2.5 text-sm font-medium
                          text-gray-900 bg-gray-200 rounded-lg
                          hover:bg-gray-300">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection