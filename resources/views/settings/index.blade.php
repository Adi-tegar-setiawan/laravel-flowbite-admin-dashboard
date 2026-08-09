@extends('layouts.dashboard')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Pengaturan Umum Aplikasi
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Kelola informasi dasar sistem, identitas perusahaan, dan logo aplikasi.
        </p>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    {{-- FORM SETTINGS --}}
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 max-w-4xl">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama Aplikasi --}}
            <div>
                <label for="app_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Aplikasi <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="app_name" 
                    name="app_name" 
                    value="{{ old('app_name', $settings['app_name']) }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    required
                >
                @error('app_name')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Perusahaan / Gudang --}}
            <div>
                <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nama Perusahaan / Gudang <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="company_name" 
                    name="company_name" 
                    value="{{ old('company_name', $settings['company_name']) }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    required
                >
                @error('company_name')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Email Perusahaan --}}
                <div>
                    <label for="company_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email Kontak Perusahaan
                    </label>
                    <input 
                        type="email" 
                        id="company_email" 
                        name="company_email" 
                        value="{{ old('company_email', $settings['company_email']) }}" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    >
                    @error('company_email')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telepon Kontak --}}
                <div>
                    <label for="company_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nomor Telepon Kontak
                    </label>
                    <input 
                        type="text" 
                        id="company_phone" 
                        name="company_phone" 
                        value="{{ old('company_phone', $settings['company_phone']) }}" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    >
                    @error('company_phone')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Upload Logo Aplikasi --}}
            <div>
                <label for="app_logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Logo Aplikasi
                </label>

                @if($settings['app_logo'])
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo saat ini" class="w-16 h-16 object-contain bg-gray-100 p-2 rounded-lg border border-gray-200">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Logo saat ini dipasang. Unggah file baru untuk mengganti.</span>
                    </div>
                @endif

                <input 
                    type="file" 
                    id="app_logo" 
                    name="app_logo" 
                    accept="image/*"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format yang didukung: PNG, JPG, JPEG, SVG (Maksimal 2MB).</p>
                @error('app_logo')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection