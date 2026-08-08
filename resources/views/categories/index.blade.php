@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Categories
        </h1>

        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($categories->count())
                    <ul class="space-y-2">
                        @foreach ($categories as $category)
                            <li class="p-3 border rounded dark:border-gray-700">
                                {{ $category->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">
                        Belum ada kategori.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection