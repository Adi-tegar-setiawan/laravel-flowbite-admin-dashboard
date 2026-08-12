@extends('layouts.dashboard')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Activity Log
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Riwayat aktivitas pengguna dan audit trail di dalam sistem Stockify.
        </p>
    </div>

    {{-- BAR PENCARIAN & FILTER AKSI --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            {{-- Input Search Deskripsi --}}
            <div class="md:col-span-1">
                <label for="search" class="sr-only">Cari Aktivitas</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                        🔍
                    </div>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari deskripsi aktivitas..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>

            {{-- Filter Jenis Aksi --}}
            <div>
                <label for="action" class="sr-only">Filter Aksi</label>
                <select id="action" 
                        name="action" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Aksi</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created (Tambah)</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated (Edit)</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                </select>
            </div>

            {{-- Tombol Filter & Reset --}}
            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                    Filter
                </button>

                @if(request('search') || request('action'))
                    <a href="{{ route('activity-logs.index') }}" 
                       class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Table Container dengan Internal Scrollbar --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">

        @if ($activities->isNotEmpty())
            {{-- AREA TABEL BER-SCROLLBAR INTERNAL --}}
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">No</th>
                            <th scope="col" class="px-6 py-3">Pengguna</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                            <th scope="col" class="px-6 py-3">Deskripsi Aktivitas</th>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($activities as $index => $activity)
                            <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">
                                {{-- Nomor --}}
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ method_exists($activities, 'firstItem') ? $activities->firstItem() + $index : $index + 1 }}
                                </td>

                                {{-- User --}}
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $activity->user?->name ?? 'System' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $activity->user?->role ?? '-' }}
                                    </div>
                                </td>

                                {{-- Action Badge --}}
                                <td class="px-6 py-4">
                                    @if ($activity->action === 'created')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900/40 dark:text-green-300">
                                            Created
                                        </span>
                                    @elseif ($activity->action === 'updated')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full dark:bg-amber-900/40 dark:text-amber-300">
                                            Updated
                                        </span>
                                    @elseif ($activity->action === 'deleted')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900/40 dark:text-red-300">
                                            Deleted
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                            {{ ucfirst($activity->action) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-200">
                                    {{ $activity->description }}
                                </td>

                                {{-- Time --}}
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                    <span class="block text-[10px] text-gray-400">({{ $activity->created_at->diffForHumans() }})</span>
                                </td>

                                {{-- Detail Button --}}
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('activity-logs.show', $activity->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer (Jika Menggunakan Pagination) --}}
            @if(method_exists($activities, 'links'))
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $activities->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                Tidak ada riwayat aktivitas yang sesuai dengan filter pencarian.
            </div>
        @endif

    </div>

</div>
@endsection