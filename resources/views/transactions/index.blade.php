@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header & Tombol Tambah --}}
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Stock Transactions
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Riwayat transaksi barang masuk dan barang keluar pada gudang.
            </p>
        </div>

        @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shrink-0">
                + Tambah Transaksi
            </a>
        @endif
    </div>

    {{-- Flash Message Success --}}
    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- BAR PENCARIAN & FILTER --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4 items-end">
            
            {{-- Input Search Produk / SKU --}}
            <div>
                <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cari Produk / SKU</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                        🔍
                    </div>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Nama produk atau SKU..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>

            {{-- Filter Tipe Transaksi --}}
            <div>
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Transaksi</label>
                <select id="type" 
                        name="type" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="Masuk" {{ request('type') === 'Masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="Keluar" {{ request('type') === 'Keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>

            {{-- Filter Tanggal Mulai --}}
            <div>
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="{{ request('start_date') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            {{-- Tombol Filter & Reset --}}
            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                    Filter
                </button>

                @if(request('search') || request('type') || request('start_date'))
                    <a href="{{ route('transactions.index') }}" 
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Table Container dengan Scroll Internal (Height 500px) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">

        @if ($transactions->count())
            {{-- AREA TABEL BER-SCROLL INTERNAL --}}
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                    <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">No</th>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Produk</th>
                            <th scope="col" class="px-6 py-3 text-center">Tipe</th>
                            <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                            <th scope="col" class="px-6 py-3">Petugas</th>
                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @foreach ($transactions as $index => $transaction)
                            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">

                                {{-- Nomor --}}
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ method_exists($transactions, 'firstItem') ? $transactions->firstItem() + $index : $index + 1 }}
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                {{-- Produk --}}
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $transaction->product?->name ?? '-' }}
                                    </div>
                                    <div class="text-xs font-mono text-gray-400">
                                        SKU: {{ $transaction->product?->sku ?? '-' }}
                                    </div>
                                </td>

                                {{-- Tipe Transaksi --}}
                                <td class="px-6 py-4 text-center">
                                    @if ($transaction->type === 'Masuk')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/40 dark:text-green-300">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900/40 dark:text-red-300">
                                            Keluar
                                        </span>
                                    @endif
                                </td>

                                {{-- Quantity --}}
                                <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white">
                                    {{ $transaction->quantity }}
                                </td>

                                {{-- User / Petugas --}}
                                <td class="px-6 py-4">
                                    {{ $transaction->user?->name ?? 'System' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/40 dark:text-blue-300">
                                        {{ $transaction->status ?? 'Completed' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('transactions.edit', $transaction->id) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 transition-colors">
                                            Edit
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-colors">
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

            {{-- Pagination Footer --}}
            @if(method_exists($transactions, 'links'))
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                Tidak ada data transaksi yang sesuai dengan kriteria pencarian.
            </div>
        @endif

    </div>

</div>

@endsection