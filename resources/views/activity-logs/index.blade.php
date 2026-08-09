@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Activity Log
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Riwayat aktivitas pengguna di dalam sistem Stockify.
        </p>
    </div>


    {{-- Activity List --}}
    <div class="bg-white rounded-lg shadow dark:bg-gray-800">

        <div class="p-4">

            @if ($activities->isNotEmpty())

                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">

                            <tr>

                                <th class="px-6 py-3">
                                    User
                                </th>

                                <th class="px-6 py-3">
                                    Aktivitas
                                </th>

                                <th class="px-6 py-3">
                                    Deskripsi
                                </th>

                                <th class="px-6 py-3">
                                    Waktu
                                </th>

                                <th class="px-6 py-3 text-right">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($activities as $activity)

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                                    {{-- User --}}
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $activity->user?->name ?? 'System' }}
                                        </div>

                                    </td>


                                    {{-- Action --}}
                                    <td class="px-6 py-4">

                                        @if ($activity->action === 'created')

                                            <span class="px-2.5 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Created
                                            </span>

                                        @elseif ($activity->action === 'updated')

                                            <span class="px-2.5 py-1 text-xs font-medium text-amber-800 bg-amber-100 rounded-full">
                                                Updated
                                            </span>

                                        @elseif ($activity->action === 'deleted')

                                            <span class="px-2.5 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                                Deleted
                                            </span>

                                        @else

                                            <span class="px-2.5 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                                {{ ucfirst($activity->action) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Description --}}
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">

                                        {{ $activity->description }}

                                    </td>


                                    {{-- Time --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        {{ $activity->created_at->format('d/m/Y H:i') }}

                                    </td>


                                    {{-- Detail --}}
                                    <td class="px-6 py-4 text-right">

                                        <a
                                            href="{{ route('activity-logs.show', $activity->id) }}"
                                            class="font-medium text-blue-600 hover:underline"
                                        >
                                            Detail
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-6 text-center">

                    <p class="text-gray-500 dark:text-gray-400">
                        Belum ada aktivitas yang tercatat.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection