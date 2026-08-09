@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Detail Activity Log
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Informasi lengkap aktivitas pengguna.
            </p>

        </div>

        <a
            href="{{ route('activity-logs.index') }}"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
        >
            Kembali
        </a>

    </div>


    {{-- Detail Card --}}
    <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- User --}}
            <div>

                <span class="block text-xs font-semibold text-gray-400 uppercase">
                    User
                </span>

                <span class="text-base font-medium text-gray-900 dark:text-white">
                    {{ $activity->user?->name ?? 'System' }}
                </span>

            </div>


            {{-- Action --}}
            <div>

                <span class="block text-xs font-semibold text-gray-400 uppercase">
                    Action
                </span>

                <span class="text-base font-medium text-gray-900 dark:text-white">
                    {{ ucfirst($activity->action) }}
                </span>

            </div>


            {{-- Subject Type --}}
            <div>

                <span class="block text-xs font-semibold text-gray-400 uppercase">
                    Subject Type
                </span>

                <span class="text-base text-gray-900 dark:text-white">
                    {{ $activity->subject_type ?? '-' }}
                </span>

            </div>


            {{-- Subject ID --}}
            <div>

                <span class="block text-xs font-semibold text-gray-400 uppercase">
                    Subject ID
                </span>

                <span class="text-base text-gray-900 dark:text-white">
                    {{ $activity->subject_id ?? '-' }}
                </span>

            </div>


            {{-- Created At --}}
            <div>

                <span class="block text-xs font-semibold text-gray-400 uppercase">
                    Waktu
                </span>

                <span class="text-base text-gray-900 dark:text-white">
                    {{ $activity->created_at->format('d/m/Y H:i:s') }}
                </span>

            </div>

        </div>


        {{-- Description --}}
        <div class="pt-6 mt-6 border-t dark:border-gray-700">

            <span class="block mb-2 text-xs font-semibold text-gray-400 uppercase">
                Deskripsi
            </span>

            <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $activity->description }}
            </p>

        </div>


        {{-- Properties --}}
        @if (!empty($activity->properties))

            <div class="pt-6 mt-6 border-t dark:border-gray-700">

                <span class="block mb-3 text-xs font-semibold text-gray-400 uppercase">
                    Properties
                </span>

                <pre class="p-4 overflow-x-auto text-sm text-gray-800 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-200">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

            </div>

        @endif

    </div>

</div>

@endsection