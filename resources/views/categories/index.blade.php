@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Kategori Produk
        </h1>

        <!-- Tombol Tambah (Triggers Modal) -->
        <button data-modal-target="create-category-modal" data-modal-toggle="create-category-modal" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
            + Tambah Kategori
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table List Categories -->
    <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
        @if ($categories->count())
            <div class="space-y-3">
                @foreach ($categories as $category)
                    <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $category->name }}
                        </span>

                        <div class="flex items-center gap-2">
                            <!-- Edit Modal Button -->
                            <button data-modal-target="edit-modal-{{ $category->id }}" data-modal-toggle="edit-modal-{{ $category->id }}" class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit per Item -->
                    <div id="edit-modal-{{ $category->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Kategori</h3>
                                </div>
                                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="p-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kategori</label>
                                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full p-2.5 text-sm border rounded-lg dark:bg-gray-600 dark:text-white">
                                    </div>
                                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @else
            <p class="text-gray-500">Belum ada kategori.</p>
        @endif
    </div>
</div>

<!-- Modal Create Category -->
<div id="create-category-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Kategori Baru</h3>
            </div>
            <form action="{{ route('categories.store') }}" method="POST" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Masukan nama kategori..." required class="w-full p-2.5 text-sm border rounded-lg dark:bg-gray-600 dark:text-white">
                </div>
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection