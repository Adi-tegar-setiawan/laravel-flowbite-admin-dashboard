@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Products
        </h1>

        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($products->count())
                    <div class="space-y-3">
                        @foreach ($products as $product)
                            <div class="p-4 border rounded-lg dark:border-gray-700">

                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    SKU: {{ $product->sku }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Kategori:
                                    {{ $product->category?->name ?? '-' }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">
                                    Supplier:
                                    {{ $product->supplier?->name ?? '-' }}
                                </div>

                                <div class="mt-2 font-medium">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <p class="text-gray-500">
                        Belum ada produk.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection