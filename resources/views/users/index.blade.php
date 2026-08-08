@extends('layouts.dashboard')

@section('content')
    <div class="p-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Manajemen Pengguna
            </h1>

            <a href="{{ route('users.create') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                + Tambah Pengguna
            </a>
        </div>

        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-4">

                @if ($users->count())
                    <div class="space-y-3">
                        @foreach ($users as $user)
                            <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">

                                {{-- Informasi User --}}
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        Email: {{ $user->email }}
                                    </div>

                                    <div class="mt-1 text-xs font-semibold inline-block px-2.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        Role: {{ $user->role }}
                                    </div>
                                </div>

                                {{-- Tombol Aksi (Edit & Delete) --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300">
                                        Edit
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @else
                    <p class="text-gray-500">
                        Belum ada pengguna.
                    </p>
                @endif

            </div>
        </div>

    </div>
@endsection