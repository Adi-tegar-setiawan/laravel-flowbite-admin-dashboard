@extends('layouts.dashboard')

@section('content')

<div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 p-3">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Stock Transactions
        </h1>

        @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
            <a
                href="{{ route('transactions.create') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
            >
                + Tambah Transaksi
            </a>
        @endif
    </div>


    {{-- Transaction List --}}
    <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="p-4">

            @if ($transactions->count())

                <div class="space-y-3">

                    @foreach ($transactions as $transaction)

                        <div class="p-4 border rounded-lg dark:border-gray-700">

                            {{-- Product --}}
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $transaction->product?->name ?? '-' }}
                            </div>

                            {{-- Type --}}
                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Tipe: {{ $transaction->type }}
                            </div>

                            {{-- Quantity --}}
                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Jumlah: {{ $transaction->quantity }}
                            </div>

                            {{-- Status --}}
                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Status: {{ $transaction->status }}
                            </div>


                            {{-- Actions --}}
                            <div class="flex items-center gap-2 mt-4">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('transactions.edit', $transaction->id) }}"
                                    class="px-3 py-2 text-sm font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600"
                                >
                                    Edit
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('transactions.destroy', $transaction->id) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-3 py-1.5 text-xs font-medium text-white
                                               bg-red-600 rounded-lg hover:bg-red-700
                                               focus:ring-4 focus:ring-red-300"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>


            @else

                <p class="text-gray-500">
                    Belum ada transaksi stok.
                </p>

            @endif

        </div>
    </div>

</div>

@endsection
