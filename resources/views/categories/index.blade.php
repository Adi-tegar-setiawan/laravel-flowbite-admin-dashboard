@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Kategori Produk
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola kelompok dan pengkategorian barang gudang.
                </p>
            </div>

            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shrink-0">
                + Tambah Kategori
            </a>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="p-4 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <form action="{{ route('categories.index') }}" method="GET" class="flex gap-2">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                        🔍
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama kategori atau deskripsi..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shrink-0">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('categories.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 shrink-0 transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table Container dengan Scroll Internal (Height 500px) --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            
            @if ($categories->count())
                {{-- Area Tabel Ber-Scroll Internal --}}
                <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Nama Kategori</th>
                                <th scope="col" class="px-6 py-3">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($categories as $index => $category)
                                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">
                                    {{-- Nomor --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ method_exists($categories, 'firstItem') ? $categories->firstItem() + $index : $index + 1 }}
                                    </td>

                                    {{-- Nama Kategori --}}
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </td>

                                    {{-- Deskripsi --}}
                                    <td class="px-6 py-4 text-gray-500 dark:text-gray-300">
                                        {{ $category->description ?? '-' }}
                                    </td>

                                    {{-- Tombol Aksi --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                               class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 transition-colors">
                                                Edit
                                            </a>

                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                @if(method_exists($categories, 'links'))
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $categories->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada data kategori yang sesuai dengan kriteria pencarian.
                </div>
            @endif

        </div>

    </div>
@endsection