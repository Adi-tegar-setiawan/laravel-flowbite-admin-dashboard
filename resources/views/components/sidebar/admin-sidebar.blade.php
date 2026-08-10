<x-sidebar-dashboard>

    {{-- DASHBOARD (Semua Role) --}}
    <x-sidebar-menu-dashboard
        routeName="dashboard"
        title="Dashboard"
    />

    {{-- USERS (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="users.index"
            title="Users"
        />
    @endif

    {{-- CATEGORIES (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="categories.index"
            title="Categories"
        />
    @endif

    {{-- SUPPLIERS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="suppliers.index"
            title="Suppliers"
        />
    @endif

    {{-- PRODUCTS (Admin & Manajer Gudang) --}}
    @if(in_array(auth()->user()->role, ['Admin', 'Manajer Gudang']))
        <x-sidebar-menu-dashboard
            routeName="products.index"
            title="Products"
        />
    @endif

    {{-- TRANSACTIONS (Semua Role) --}}
    <x-sidebar-menu-dashboard
        routeName="transactions.index"
        title="Transactions"
    />

    {{-- STOCK OPNAME (Hanya Admin dan Manajer Gudang) --}}
    @if(auth()->user()->role === ['Admin', 'Manajer Gudang'])
        <x-sidebar-menu-dashboard
            routeName="stock-opnames.index"
            title="Stock Opname"
        />
    @endif

    {{-- ACTIVITY LOG (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="activity-logs.index"
            title="Activity Log"
        />
    @endif

    {{-- LAPORAN / REPORTS (Admin & Manajer Gudang) --}}
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

    {{-- SETTINGS (Hanya Admin) --}}
    @if(auth()->user()->role === 'Admin')
        <x-sidebar-menu-dashboard
            routeName="settings.index"
            title="Settings"
        />
    @endif

</x-sidebar-dashboard>