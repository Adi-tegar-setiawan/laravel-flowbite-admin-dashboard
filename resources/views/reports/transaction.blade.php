@extends('layouts.dashboard')

@section('content')
<div class="p-6">

    {{-- KOP LAPORAN (HANYA MUNCUL SAAT PRINT) --}}
    <div class="hidden print:block mb-6 border-b-2 border-gray-800 pb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold uppercase text-black">
                    {{ $appSettings['company_name'] ?? $appSettings['app_name'] ?? 'STOCKIFY WAREHOUSE' }}
                </h1>
                <p class="text-xs text-gray-600">Sistem Manajemen Stok & Inventaris Gudang</p>
                @if(!empty($appSettings['company_email']))
                    <p class="text-xs text-gray-500">Email: {{ $appSettings['company_email'] }} | Telp: {{ $appSettings['company_phone'] ?? '-' }}</p>
                @endif
            </div>
            <div class="text-right text-xs text-gray-600">
                <p><span class="font-semibold">Laporan:</span> Transaksi Barang ({{ $selectedType ?? 'Masuk & Keluar' }})</p>
                <p><span class="font-semibold">Periode:</span> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                <p><span class="font-semibold">Dicetak Oleh:</span> {{ auth()->user()->name }} ({{ now()->translatedFormat('d/m/Y H:i') }})</p>
            </div>
        </div>
    </div>

    {{-- HEADER (TAMPILAN WEB) --}}
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between print:hidden">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Laporan Transaksi Barang
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Rekapitulasi riwayat barang masuk dan barang keluar berdasarkan rentang tanggal.
            </p>
        </div>

        <button 
            onclick="window.print()" 
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 shrink-0"
        >
            🖨 Print Laporan
        </button>
    </div>

    {{-- FILTER FORM (HIDDEN SAAT PRINT) --}}
    <div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 print:hidden">
        <form method="GET" action="{{ route('reports.transactions') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
            <div>
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Tanggal Mulai
                </label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    value="{{ $startDate }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                >
            </div>

            <div>
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Tanggal Selesai
                </label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    value="{{ $endDate }}" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                >
            </div>

            <div>
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Tipe Transaksi
                </label>
                <select 
                    id="type" 
                    name="type" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                >
                    <option value="">Semua Tipe</option>
                    <option value="Masuk" {{ $selectedType === 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="Keluar" {{ $selectedType === 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button 
                    type="submit" 
                    class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 transition-colors"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('reports.transactions') }}" 
                    class="px-4 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 transition-colors"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL LAPORAN TRANSAKSI DENGAN SCROLL INTERNAL --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden print:border-none print:shadow-none">
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar print:max-h-none print:overflow-visible">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 print:text-black print:w-full">
                <thead class="sticky top-0 z-10 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 print:bg-gray-200 print:text-black print:static">
                    <tr class="print:border-b-2 print:border-black">
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">No</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Tanggal</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Produk</th>
                        <th scope="col" class="px-6 py-3 text-center print:px-2 print:py-1">Tipe</th>
                        <th scope="col" class="px-6 py-3 text-center print:px-2 print:py-1">Jumlah</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Petugas</th>
                        <th scope="col" class="px-6 py-3 print:px-2 print:py-1">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 print:divide-gray-300">
                    @forelse ($transactions as $index => $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 print:border-b print:border-gray-300 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 print:text-black print:px-2 print:py-2">
                                {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $transaction->product?->name ?? '-' }}
                                <div class="text-xs font-normal text-gray-400 print:text-gray-600">SKU: {{ $transaction->product?->sku ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center print:px-2 print:py-2">
                                @if ($transaction->type === 'Masuk')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900/30 dark:text-green-300 print:bg-transparent print:text-green-700 print:font-bold">
                                        Masuk
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900/30 dark:text-red-300 print:bg-transparent print:text-red-700 print:font-bold">
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white print:text-black print:px-2 print:py-2">
                                {{ $transaction->quantity }}
                            </td>
                            <td class="px-6 py-4 print:text-black print:px-2 print:py-2">
                                {{ $transaction->user?->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 print:text-black print:px-2 print:py-2">
                                {{ $transaction->status ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 print:text-black">
                                Tidak ada data transaksi pada periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TANDA TANGAN (HANYA MUNCUL SAAT PRINT) --}}
    <div class="hidden print:grid grid-cols-2 gap-4 mt-12 text-xs text-center text-black">
        <div>
            <p>Dibuat Oleh,</p>
            <div class="h-16"></div>
            <p class="font-semibold underline">{{ auth()->user()->name }}</p>
            <p class="text-gray-500">Staff / Admin Gudang</p>
        </div>
        <div>
            <p>Mengetahui,</p>
            <div class="h-16"></div>
            <p class="font-semibold underline">( .................................... )</p>
            <p class="text-gray-500">Manajer Gudang</p>
        </div>
    </div>

</div>

{{-- CSS KHUSUS PRINT CETAK PRO --}}
<style>
@media print {
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    aside, header, footer, .sidebar, [class*="sidebar"] {
        display: none !important;
    }
    main {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endsection