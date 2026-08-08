@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Transaksi Stok
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Catat penerimaan atau pengeluaran stok barang.
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

            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Product --}}
                    <div>
                        <label for="product_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Pilih Produk
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (SKU: {{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Jenis Transaksi
                        </label>

                        <select
                            id="type"
                            name="type"
                            required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Masuk" {{ old('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="Keluar" {{ old('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label for="quantity"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Jumlah (Quantity)
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity', 1) }}"
                            required
                            min="1"
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            id="date"
                            name="date"
                            value="{{ old('date', date('Y-m-d')) }}"
                            required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="Dikeluarkan" {{ old('status') == 'Dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                            <option value="Ditolak" {{ old('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label for="notes"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Catatan (Opsional)
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Catatan tambahan..."
                            class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 mt-6">

                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Simpan Transaksi
                    </button>

                    <a
                        href="{{ route('transactions.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>

                </div>

            </form>

        </div>
    </div>
@endsection