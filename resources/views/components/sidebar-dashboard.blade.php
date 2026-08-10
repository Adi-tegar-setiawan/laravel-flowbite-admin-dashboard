<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 lg:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">

            {{-- 1. DASHBOARD (Semua Role) --}}
            <x-sidebar-menu-dashboard routeName="dashboard" title="Dashboard">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm9-3a1 1 0 10-2 0v3a1 1 0 00.4.8l2 1.5a1 1 0 001.2-1.6L11 9.4V7z"></path>
                </svg>
            </x-sidebar-menu-dashboard>

            {{-- 2. USERS (Hanya Admin) --}}
            @if(auth()->user()->role === 'Admin')
                <x-sidebar-menu-dashboard routeName="users.index" title="Users">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 3. CATEGORIES (Hanya Admin) --}}
            @if(auth()->user()->role === 'Admin')
                <x-sidebar-menu-dashboard routeName="categories.index" title="Categories">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm14 1a1 1 0 11-2 0 1 1 0 012 0zM2 13a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2zm14 1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 4. SUPPLIERS (Admin & Manajer) --}}
            @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                <x-sidebar-menu-dashboard routeName="suppliers.index" title="Suppliers">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 5. PRODUCTS (Admin & Manajer) --}}
            @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                <x-sidebar-menu-dashboard routeName="products.index" title="Products">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4a1 1 0 00-1 1v1a1 1 0 002 0V5a1 1 0 00-1-1zm12 0a1 1 0 00-1 1v1a1 1 0 002 0V5a1 1 0 00-1-1zM3 8a1 1 0 011-1h12a1 1 0 011 1v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm3 3a1 1 0 100 2 1 1 0 000-2zm5 0a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 6. TRANSACTIONS (Semua Role) --}}
            <x-sidebar-menu-dashboard routeName="transactions.index" title="Transactions">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"></path>
                </svg>
            </x-sidebar-menu-dashboard>

            {{-- 7. STOCK OPNAME (Admin, Manajer Gudang, & Staff Gudang) --}}
            @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang', 'Staff Gudang']))
                <x-sidebar-menu-dashboard routeName="stock-opnames.index" title="Stock Opname">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 8. ACTIVITY LOG (Hanya Admin) - Sesuai Route: activity-logs.index --}}
            @if(auth()->user()->role === 'Admin')
                <x-sidebar-menu-dashboard routeName="activity-logs.index" title="Activity Log">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 9. LAPORAN STOK & TRANSAKSI (Admin & Manajer) --}}
            @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
                <x-sidebar-menu-dashboard routeName="reports.stock" title="Laporan Stok">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 100 2h4a1 1 0 100-2H8zm0-3a1 1 0 100 2h4a1 1 0 100-2H8z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>

                <x-sidebar-menu-dashboard routeName="reports.transactions" title="Laporan Transaksi">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

            {{-- 10. SETTINGS (Hanya Admin) --}}
            @if(auth()->user()->role === 'Admin')
                <x-sidebar-menu-dashboard routeName="settings.index" title="Settings">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                </x-sidebar-menu-dashboard>
            @endif

        </ul>
    </div>
</aside>