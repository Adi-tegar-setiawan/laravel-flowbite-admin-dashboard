@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Activity Log
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Riwayat aktivitas pengguna dalam sistem Stockify.
        </p>
    </div>


    {{-- Activity List --}}
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">

        <div class="p-6">

            @if ($activities->count())

                <div class="space-y-4">

                    @foreach ($activities as $activity)

                        <div class="flex gap-4 p-4 border rounded-lg dark:border-gray-700">

                            {{-- Icon --}}
                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900">

                                <span class="text-blue-600 dark:text-blue-300">
                                    ✓
                                </span>

                            </div>


                            {{-- Content --}}
                            <div class="flex-1">

                                <div class="flex items-center justify-between gap-4">

                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $activity->description }}
                                    </p>

                                    <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $activity->created_at->format('d M Y H:i') }}
                                    </span>

                                </div>


                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">

                                    Oleh:
                                    <span class="font-medium">
                                        {{ $activity->user?->name ?? 'System' }}
                                    </span>

                                </p>


                                <span class="inline-block px-2.5 py-1 mt-2 text-xs font-medium rounded-full
                                    bg-blue-100 text-blue-800
                                    dark:bg-blue-900 dark:text-blue-300">

                                    {{ ucfirst($activity->action) }}

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $activities->links() }}
                </div>

            @else

                <div class="p-4 text-sm text-gray-500 bg-gray-50 rounded-lg dark:bg-gray-700 dark:text-gray-400">
                    Belum ada aktivitas.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection