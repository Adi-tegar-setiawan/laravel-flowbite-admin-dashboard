@extends('layouts.dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
        Edit Atribut Produk
    </h1>

    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Ubah informasi atribut produk.
    </p>
</div>

<div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">

    <form
        action="{{ route(
            'products.attributes.update',
            [
                'productId' => $productId,
                'attributeId' => $attribute->id,
            ]
        ) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        {{-- Nama Attribute --}}
        <div class="mb-5">

            <label
                for="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
                Nama Atribut
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $attribute->name) }}"
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
        <div class="mb-5">

            <label
                for="value"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
                Nilai
            </label>

            <input
                type="text"
                id="value"
                name="value"
                value="{{ old('value', $attribute->value) }}"
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

        <div class="flex gap-3">

            <button
                type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600
                       rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
            >
                Simpan Perubahan
            </button>

            <a
                href="{{ route('products.show', $productId) }}"
                class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200
                       rounded-lg hover:bg-gray-300"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection
