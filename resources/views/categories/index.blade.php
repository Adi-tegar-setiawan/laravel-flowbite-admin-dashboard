@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Kategori Produk
            </h1>

            <a href="{{ route('categories.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                + Tambah Kategori
            </a>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($categories->count())
                    <div class="space-y-3">
                        @foreach ($categories as $category)
                            <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">

                                {{-- Informasi Kategori --}}
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </div>

                                    @if($category->description)
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->description }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Tombol Aksi (Edit & Delete) --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300">
                                        Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                @else
                    <p class="text-gray-500">
                        Belum ada kategori.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection