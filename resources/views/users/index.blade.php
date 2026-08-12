@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Manajemen Pengguna
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola akun pengguna dan hak akses sistem.
                </p>
            </div>

            <a href="{{ route('users.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors shrink-0">
                + Tambah Pengguna
            </a>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        {{-- BAR PENCARIAN & FILTER ROLE --}}
        <div class="p-4 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                
                {{-- Input Search --}}
                <div class="md:col-span-1">
                    <label for="search" class="sr-only">Cari Pengguna</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                            🔍
                        </div>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama atau email..."
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

                {{-- Filter Role --}}
                <div>
                    <label for="role" class="sr-only">Filter Role</label>
                    <select id="role" 
                            name="role" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Role</option>
                        <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Manajer Gudang" {{ request('role') === 'Manajer Gudang' ? 'selected' : '' }}>Manajer Gudang</option>
                        <option value="Staff Gudang" {{ request('role') === 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                    </select>
                </div>

                {{-- Tombol Filter & Reset --}}
                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors">
                        Filter
                    </button>

                    @if(request('search') || request('role'))
                        <a href="{{ route('users.index') }}" 
                           class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
            
            @if ($users->count())
                {{-- SCROLL INTERNAL DENGAN MAX HEIGHT --}}
                <div class="overflow-x-auto max-h-[420px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-16">No</th>
                                <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Role</th>
                                <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($users as $index => $user)
                                <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $users->firstItem() + $index }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->role === 'Admin')
                                            <span class="px-2.5 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full dark:bg-purple-900/40 dark:text-purple-300">
                                                Admin
                                            </span>
                                        @elseif($user->role === 'Manajer Gudang')
                                            <span class="px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900/40 dark:text-blue-300">
                                                Manajer Gudang
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                Staff Gudang
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 transition-colors">
                                                Edit
                                            </a>

                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-colors">
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

                {{-- Pagination Footer --}}
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->withQueryString()->links() }}
                </div>
            @else
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    Tidak ditemukan data pengguna yang sesuai dengan kriteria pencarian.
                </div>
            @endif

        </div>

    </div>
@endsection