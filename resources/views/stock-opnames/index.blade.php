@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Stock Opname
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola pemeriksaan dan pencatatan stok fisik produk.
            </p>
        </div>

        <a href="{{ route('stock-opnames.create') }}"
           class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg
                  hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
            + Tambah Stock Opname
        </a>

    </div>


    {{-- Flash Message --}}
    @if (session('success'))

        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg
                    bg-green-50 dark:bg-gray-800 dark:text-green-400">

            {{ session('success') }}

        </div>

    @endif


    {{-- Error Message --}}
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


    {{-- Table --}}
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">

        <div class="p-4">

            @if ($opnames->count())

                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                        <thead class="text-xs text-gray-700 uppercase
                                      bg-gray-100 dark:bg-gray-700 dark:text-gray-400">

                            <tr>

                                <th class="px-6 py-3">
                                    Tanggal
                                </th>

                                <th class="px-6 py-3">
                                    Produk
                                </th>

                                <th class="px-6 py-3">
                                    Stok Sistem
                                </th>

                                <th class="px-6 py-3">
                                    Stok Fisik
                                </th>

                                <th class="px-6 py-3">
                                    Selisih
                                </th>

                                <th class="px-6 py-3">
                                    Petugas
                                </th>

                                <th class="px-6 py-3 text-right">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($opnames as $opname)

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                    {{-- Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        {{ $opname->date?->format('d/m/Y') ?? '-' }}

                                    </td>


                                    {{-- Produk --}}
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-900 dark:text-white">

                                            {{ $opname->product?->name ?? '-' }}

                                        </div>

                                        <div class="text-xs text-gray-500">

                                            SKU:
                                            {{ $opname->product?->sku ?? '-' }}

                                        </div>

                                    </td>


                                    {{-- System Stock --}}
                                    <td class="px-6 py-4">

                                        {{ $opname->system_stock }}

                                    </td>


                                    {{-- Physical Stock --}}
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">

                                        {{ $opname->physical_stock }}

                                    </td>


                                    {{-- Difference --}}
                                    <td class="px-6 py-4">

                                        @if ($opname->difference > 0)

                                            <span class="font-semibold text-green-600 dark:text-green-400">
                                                +{{ $opname->difference }}
                                            </span>

                                        @elseif ($opname->difference < 0)

                                            <span class="font-semibold text-red-600 dark:text-red-400">
                                                {{ $opname->difference }}
                                            </span>

                                        @else

                                            <span class="font-semibold text-gray-600 dark:text-gray-400">
                                                0
                                            </span>

                                        @endif

                                    </td>


                                    {{-- User --}}
                                    <td class="px-6 py-4">

                                        {{ $opname->user?->name ?? '-' }}

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">

                                        <div class="flex justify-end items-center gap-3">

                                            {{-- Edit --}}
                                            <a href="{{ route(
                                                'stock-opnames.edit',
                                                $opname->id
                                            ) }}"
                                               class="font-medium text-blue-600 hover:underline">

                                                Edit

                                            </a>


                                            {{-- Delete --}}
                                            <form action="{{ route(
                                                'stock-opnames.destroy',
                                                $opname->id
                                            ) }}"
                                                  method="POST"
                                                  class="inline">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="font-medium text-red-600 hover:underline"
                                                        onclick="return confirm(
                                                            'Apakah Anda yakin ingin menghapus stock opname ini?'
                                                        )">

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


                {{-- Pagination --}}
                <div class="mt-6">

                    {{ $opnames->links() }}

                </div>

            @else

                <div class="p-6 text-center">

                    <p class="text-gray-500 dark:text-gray-400">
                        Belum ada data stock opname.
                    </p>

                    <a href="{{ route('stock-opnames.create') }}"
                       class="inline-block mt-4 px-4 py-2 text-sm font-medium
                              text-white bg-blue-600 rounded-lg hover:bg-blue-700">

                        Tambah Stock Opname

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection