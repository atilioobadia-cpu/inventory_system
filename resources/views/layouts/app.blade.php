<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mtokoma Motorcycle Parts')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1E293B',
                        'navy-light': '#334155',
                        'navy-dark': '#0F172A',
                        electric: '#3B82F6',
                        success: '#10B981',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        'off-white': '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); }
        .sidebar-link.active { background: rgba(59,130,246,0.2); color: #3B82F6; border-right: 3px solid #3B82F6; }
        .sidebar-collapse { width: 4rem; }
        .sidebar-collapse .sidebar-text { display: none; }
        .sidebar-collapse .sidebar-logo-text { display: none; }
        .sidebar-expanded { width: 16rem; }
        @media (max-width: 1023px) {
            .sidebar-expanded { transform: translateX(-100%); }
            .sidebar-expanded.open { transform: translateX(0); }
        }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body class="bg-off-white font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenu: false }">

    {{-- Mobile Overlay --}}
    <div x-show="mobileMenu" x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
         @click="mobileMenu = false">
    </div>

    {{-- Sidebar --}}
    <aside class="fixed left-0 top-0 h-full bg-navy z-50 transition-all duration-300 flex flex-col
         sidebar-expanded lg:sidebar-expanded"
         :class="mobileMenu ? 'open' : ''"
         x-bind:class="sidebarOpen ? 'lg:sidebar-expanded' : 'lg:sidebar-collapse'">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 border-b border-slate-700 flex-shrink-0">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-electric flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1h2m10 1l2-1V8a1 1 0 00-1-1h-2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 6h2a2 2 0 012 2v4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="sidebar-logo-text ml-3 text-white font-bold text-lg whitespace-nowrap">Mtokoma</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto scrollbar-thin py-4">
            <div class="px-3 space-y-1">
                {{-- Dashboard --}}
                @can('view_dashboard')
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Dashboard</span>
                </a>
                @endcan

                {{-- Items --}}
                @can('view_items')
                <a href="{{ route('items.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Items</span>
                </a>
                @endcan

                {{-- Categories --}}
                @can('view_categories')
                <a href="{{ route('categories.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Categories</span>
                </a>
                @endcan

                {{-- Suppliers --}}
                @can('view_suppliers')
                <a href="{{ route('suppliers.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-2.25h7.5m-7.5 0H6.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125H9"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Suppliers</span>
                </a>
                @endcan

                {{-- Customers --}}
                @can('view_customers')
                <a href="{{ route('customers.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Customers</span>
                </a>
                @endcan

                {{-- Purchases --}}
                @can('view_purchases')
                <a href="{{ route('purchases.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.183A1.125 1.125 0 0 0 18.056 3H5.106m2.394 11.25L7.5 14.25m0 0h9.75m-9.75 0L6.375 3M20.25 7.5H21"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Purchases</span>
                </a>
                @endcan

                {{-- Sales --}}
                @can('view_sales')
                <a href="{{ route('sales.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12h4.5m-4.5 0a2 2 0 0 1-1.732-1M12 18h4.5m-4.5 0a2 2 0 0 0 1.732-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Sales</span>
                </a>
                @endcan

                {{-- POS --}}
                @can('access_pos')
                <a href="{{ route('pos.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white bg-electric/20 rounded-lg text-sm
                          {{ request()->routeIs('pos.*') ? 'bg-electric/30' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-electric" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap font-semibold text-electric">POS</span>
                </a>
                @endcan

                {{-- Stock --}}
                @can('view_stock')
                <a href="{{ route('stock.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Stock</span>
                </a>
                @endcan

                {{-- Reconciliations --}}
                @can('view_reconciliations')
                <a href="{{ route('reconciliations.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Reconciliations</span>
                </a>
                @endcan

                {{-- Expenses --}}
                @can('view_expenses')
                <a href="{{ route('expenses.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.414-1.42a2 2 0 0 1 2.828 0l1.414 1.42.879-.659M12 18V6m0 12H7.5m4.5 0h4.5"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Expenses</span>
                </a>
                @endcan

                {{-- Reports --}}
                @can('view_reports')
                <a href="{{ route('reports.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Reports</span>
                </a>
                @endcan

                {{-- Import/Export --}}
                @can('import_export_data')
                <a href="{{ route('import-export.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('import-export.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Import / Export</span>
                </a>
                @endcan

                {{-- Settings Divider --}}
                <div class="pt-4 pb-2 px-3">
                    <p class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Settings</p>
                </div>

                {{-- Roles & Permissions --}}
                @can('manage_roles')
                <a href="{{ route('roles.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Roles & Permissions</span>
                </a>
                @endcan

                {{-- Users --}}
                @can('view_users')
                <a href="{{ route('users.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Users</span>
                </a>
                @endcan

                {{-- Settings --}}
                @can('manage_settings')
                <a href="{{ route('settings.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Settings</span>
                </a>
                @endcan

                {{-- Activity Log --}}
                @can('view_activity_log')
                <a href="{{ route('activity-log.index') }}"
                   class="sidebar-link flex items-center px-3 py-2.5 text-slate-300 rounded-lg text-sm
                          {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Activity Log</span>
                </a>
                @endcan
            </div>
        </nav>

        {{-- User Info at Bottom --}}
        <div class="flex-shrink-0 border-t border-slate-700 p-3">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-electric/20 flex items-center justify-center flex-shrink-0">
                    <span class="text-electric text-sm font-semibold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                </div>
                <div class="sidebar-text ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role->name ?? 'Admin' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-text">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="transition-all duration-300 lg:ml-64" x-bind:class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                {{-- Mobile Hamburger --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Desktop Sidebar Toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Page Title --}}
                <h1 class="text-lg font-semibold text-gray-800">{{ $pageTitle ?? '' }} @yield('header-title', '')</h1>
            </div>

            <div class="flex items-center gap-4">
                {{-- Search --}}
                <div class="hidden md:block relative">
                    <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-electric/50 focus:border-electric bg-gray-50">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>

                {{-- Notifications --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                        </svg>
                        @if(isset($notifications) && $notifications->count() > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-danger rounded-full text-white text-[10px] flex items-center justify-center">5</span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">Notifications</p>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50">
                                <p class="text-sm text-gray-800">Low stock alert for "Brake Pads"</p>
                                <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50">
                                <p class="text-sm text-gray-800">New purchase order #PO-1234 created</p>
                                <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100">
                            <a href="#" class="text-sm text-electric hover:underline">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- User Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-electric flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role->name ?? 'Admin' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            My Profile
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-danger hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Breadcrumbs --}}
        @hasSection('breadcrumbs')
        <div class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3">
            <nav class="flex items-center text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-electric transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                </a>
                @yield('breadcrumbs')
            </nav>
        </div>
        @endif

        {{-- Flash Messages --}}
        @include('components.flash-messages')

        {{-- Page Content --}}
        <main class="p-4 lg:p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-200 bg-white px-4 lg:px-6 py-4">
            <p class="text-sm text-gray-500 text-center">&copy; 2026 Mtokoma Motorcycle Parts. All rights reserved.</p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>