@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Stock Transactions
        </h1>

        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($transactions->count())

                    <div class="space-y-3">
                        @foreach ($transactions as $transaction)
                            <div class="p-4 border rounded-lg dark:border-gray-700">

                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->product?->name ?? '-' }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Tipe: {{ $transaction->type }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Jumlah: {{ $transaction->quantity }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Status: {{ $transaction->status }}
                                </div>

                            </div>
                        @endforeach
                    </div>

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