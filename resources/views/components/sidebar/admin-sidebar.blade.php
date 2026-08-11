<x-sidebar-dashboard>

    {{-- 1. DASHBOARD (Semua Role: Admin, Manajer Gudang, Staff Gudang) --}}
    <x-sidebar-menu-dashboard
        routeName="dashboard"
        title="Dashboard"
    />

    {{-- 2. USERS (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="users.index"
            title="Users"
        />
    @endif

    {{-- 3. CATEGORIES (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="categories.index"
            title="Categories"
        />
    @endif

    {{-- 4. SUPPLIERS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="suppliers.index"
            title="Suppliers"
        />
    @endif

    {{-- 5. PRODUCTS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="products.index"
            title="Products"
        />
    @endif

    {{-- 6. TRANSACTIONS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="transactions.index"
            title="Transactions"
        />
    @endif

    {{-- 7. STOCK OPNAME (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="stock-opnames.index"
            title="Stock Opname"
        />
    @endif

    {{-- 8. ACTIVITY LOG (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="activity-logs.index"
            title="Activity Log"
        />
    @endif

    {{-- 9. LAPORAN / REPORTS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="reports.stock"
            title="Laporan Stok"
        />

        <x-sidebar-menu-dashboard
            routeName="reports.transactions"
            title="Laporan Transaksi"
        />
    @endif

    {{-- 10. SETTINGS (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="settings.index"
            title="Settings"
        />
    @endif

</x-sidebar-dashboard>