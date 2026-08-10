@props(['routeName', 'title'])

@php
    $isActive = request()->routeIs($routeName) || request()->routeIs($routeName . '.*');
@endphp

<li>
    <a href="{{ route($routeName) }}"
       class="flex items-center p-2 text-base font-normal rounded-lg transition duration-75 group {{ $isActive ? 'bg-blue-600 text-white dark:bg-blue-600 dark:text-white' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
        
        {{-- Ikon SVG dimasukkan via slot --}}
        <span class="w-6 h-6 flex-shrink-0 transition duration-75 {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}">
            {{ $slot }}
        </span>

        <span class="ml-3 flex-1 whitespace-nowrap">{{ $title }}</span>
    </a>
</li>