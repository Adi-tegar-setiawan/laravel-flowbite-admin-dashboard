<footer class="p-4 my-6 mx-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
    {{-- Sisi Kiri: Copyright Dinamis --}}
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
        &copy; {{ date('Y') }} 
        <a href="{{ url('/') }}" class="font-semibold text-gray-700 dark:text-gray-200 hover:underline">
            {{ $appSettings['company_name'] ?? $appSettings['app_name'] ?? 'Stockify' }}
        </a>. All rights reserved.
    </span>

    {{-- Sisi Kanan: Informasi Versi / Status Sistem --}}
    <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
        <li class="mr-4 md:mr-6">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                System Active
            </span>
        </li>
        <li>
            <span class="text-xs text-gray-400 dark:text-gray-500">
                Stockify v1.0.0
            </span>
        </li>
    </ul>
</footer>