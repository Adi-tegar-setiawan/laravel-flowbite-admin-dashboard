<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="#">
    <meta name="author" content="#">
    <meta name="generator" content="Laravel">

    <title>Dashboard - Stockify</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="canonical" href="{{ request()->fullUrl() }}">

    @if(isset($page->params['robots']))
        <meta name="robots" content="{{ $page->params['robots'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    {{-- CSS KHUSUS PRINT GLOBAL --}}
    <style>
    @media print {
        /* Sembunyikan semua elemen navigasi, header, sidebar, footer, dan debugbar */
        nav, 
        header, 
        aside, 
        footer, 
        .print\:hidden,
        #phpdebugbar, 
        .phpdebugbar-minified {
            display: none !important;
        }

        /* Reset background, margin, dan padding ke kertas putih */
        html, body {
            background-color: #ffffff !important;
            color: #000000 !important;
            background: #ffffff !important;
        }

        /* Paksa area konten agar memenuhi seluruh kertas */
        #main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            position: static !important;
            overflow: visible !important;
            background-color: #ffffff !important;
        }

        .flex.pt-16 {
            padding-top: 0 !important;
        }
    }
    </style>
</head>
@php
    $whiteBg = isset($params['white_bg']) && $params['white_bg'];
@endphp
<body class="{{ $whiteBg ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}">

    {{-- NAVBAR (SEMBUNYIKAN SAAT PRINT) --}}
    <div class="print:hidden">
        <x-navbar-dashboard/>
    </div>

    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 print:pt-0 print:bg-white">
        
        {{-- SIDEBAR (SEMBUNYIKAN SAAT PRINT) --}}
        <div class="print:hidden">
            <x-sidebar.admin-sidebar/>
        </div>

        {{-- MAIN CONTENT --}}
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900 print:ml-0 print:bg-white print:p-0">
            <main>
                @yield('content')
            </main>

            {{-- FOOTER (SEMBUNYIKAN SAAT PRINT) --}}
            <div class="print:hidden">
                <x-footer-dashboard/>
            </div>
        </div>

    </div>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>
</body>
</html>