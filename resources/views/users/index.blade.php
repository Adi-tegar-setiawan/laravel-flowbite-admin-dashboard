@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Users
        </h1>

        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($users->count())
                    <ul class="space-y-2">
                        @foreach ($users as $user)
                            <li class="p-3 border rounded dark:border-gray-700">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">
                        Belum ada user.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection