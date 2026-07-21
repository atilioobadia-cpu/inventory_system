<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mtokoma Motorcycle Parts')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tz-green': '#00A651',
                        'tz-green-dark': '#008C44',
                        'tz-green-darker': '#006B35',
                        'tz-green-light': '#E6F7EE',
                        'tz-gold': '#FCD116',
                        'tz-gold-dark': '#E5BE10',
                        'tz-gold-light': '#FEF9D8',
                        'tz-blue': '#003DA5',
                        'tz-blue-light': '#E8EEF9',
                        'tz-dark': '#1A1A1A',
                        'tz-sidebar': '#003D23',
                        'tz-sidebar-hover': 'rgba(255,255,255,0.08)',
                        'tz-sidebar-active': 'rgba(252,209,22,0.15)',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* Sidebar */
        .sidebar-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }
        .sidebar-link.active {
            background: rgba(0,166,81,0.12);
            color: #00A651;
            border-left-color: #00A651;
        }
        .sidebar-link.active svg { color: #00A651; }
        .sidebar-collapse { width: 4rem; }
        .sidebar-collapse .sidebar-text { display: none; }
        .sidebar-collapse .sidebar-logo-text { display: none; }
        .sidebar-collapse .sidebar-section-label { display: none; }
        .sidebar-expanded { width: 16rem; }
        @media (max-width: 1023px) {
            .sidebar-expanded { transform: translateX(-100%); }
            .sidebar-expanded.open { transform: translateX(0); }
        }

        /* Section labels */
        .sidebar-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.35);
            padding: 1rem 0.75rem 0.375rem;
        }

        /* Scrollbar */
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        /* Page Transition */
        .page-content {
            animation: fadeInDown 0.4s ease forwards;
            opacity: 0;
        }
        @keyframes fadeInDown {
            0% { opacity: 0; transform: translateY(-12px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Loading Bar */
        .loading-bar {
            position: fixed; top: 0; left: 0; height: 3px; z-index: 9999;
            background: #00A651;
            transition: width 0.3s ease;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed; top: 20px; right: 20px; z-index: 10000;
            display: flex; flex-direction: column; gap: 10px;
            pointer-events: none;
        }
        .toast-container > * { pointer-events: auto; }
        .toast {
            min-width: 340px; max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform: translateX(120%);
            opacity: 0;
        }
        .toast.removing {
            animation: toastSlideOut 0.3s cubic-bezier(0.4, 0, 1, 1) forwards;
        }
        @keyframes toastSlideIn {
            0% { transform: translateX(120%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastSlideOut {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(120%); opacity: 0; }
        }
        .toast-progress {
            height: 3px; position: absolute; bottom: 0; left: 0; right: 0;
            animation: toastProgress 4s linear forwards;
        }
        @keyframes toastProgress {
            0% { width: 100%; }
            100% { width: 0%; }
        }

        /* Form Inputs */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="url"],
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        textarea,
        select {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.625rem 0.875rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            color: #111827 !important;
            width: 100% !important;
            transition: all 0.15s ease;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #00A651 !important;
            box-shadow: 0 0 0 3px rgba(0,166,81,0.12) !important;
            outline: none !important;
        }
        input:hover, textarea:hover, select:hover {
            border-color: #9ca3af !important;
        }
        input::placeholder, textarea::placeholder {
            color: #9ca3b8 !important;
            font-style: normal !important;
        }
        select {
            appearance: auto !important;
            background-image: none !important;
        }
        select option {
            background: #ffffff;
            color: #111827;
            padding: 0.5rem;
        }
        textarea {
            resize: vertical !important;
            min-height: 5rem;
        }

        /* Form Labels */
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
            letter-spacing: 0.01em;
        }
        .form-label .required { color: #EF4444; margin-left: 2px; }

        /* Form Group Spacing */
        .form-group { margin-bottom: 1.25rem; }

        /* Form Card */
        .form-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.75rem;
        }

        /* Error messages */
        .form-error {
            font-size: 0.75rem;
            color: #EF4444;
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Sidebar link transitions */
        .sidebar-link:active { transform: scale(0.98); }

        /* Button styles */
        .btn-primary {
            background: #00A651;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover { background: #008C44; }
        .btn-primary:active { transform: scale(0.97); }

        .btn-secondary {
            background: white;
            color: #374151;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: 1px solid #d1d5db;
            cursor: pointer;
        }
        .btn-secondary:hover { background: #f9fafb; border-color: #9ca3af; }

        .btn-danger {
            background: #DC2626;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .btn-danger:hover { background: #B91C1C; }
        .btn-danger:active { transform: scale(0.97); }

        /* Card hover */
        .card-hover { transition: all 0.25s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }

        /* Smooth link transitions */
        a[href] { transition: color 0.15s ease; }

        /* Green badge */
        .badge-gold {
            background: #FCD116;
            color: #1A1A1A;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            line-height: 1.25rem;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenu: false }">

    {{-- Loading Bar --}}
    <div id="loading-bar" class="loading-bar" style="width: 0%; display: none;"></div>

    {{-- Toast Notifications --}}
    <div class="toast-container" x-data="toastManager()" x-init="init()">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="toast relative" :class="toast.removing ? 'removing' : ''"
                 x-init="setTimeout(() => startProgress(toast), 100)">
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl"
                     :class="{
                         'bg-emerald-500': toast.type === 'success',
                         'bg-red-500': toast.type === 'error',
                         'bg-amber-500': toast.type === 'warning',
                         'bg-blue-500': toast.type === 'info'
                     }"></div>
                <div class="flex items-start gap-3 p-4 pl-5">
                    <div class="flex-shrink-0 mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900" x-text="toast.title"></p>
                        <p class="text-sm text-gray-500 mt-0.5" x-text="toast.message" x-show="toast.message"></p>
                    </div>
                    <button @click="remove(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="toast-progress rounded-b-xl"
                     :class="{
                         'bg-emerald-500/30': toast.type === 'success',
                         'bg-red-500/30': toast.type === 'error',
                         'bg-amber-500/30': toast.type === 'warning',
                         'bg-blue-500/30': toast.type === 'info'
                     }"
                     :style="'animation-duration: ' + (toast.duration || 4) + 's'"></div>
            </div>
        </template>
    </div>

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
    <aside class="fixed left-0 top-0 h-full z-50 transition-all duration-300 flex flex-col
         sidebar-expanded lg:sidebar-expanded"
         style="background: #003D23;"
         :class="mobileMenu ? 'open' : ''"
         x-bind:class="sidebarOpen ? 'lg:sidebar-expanded' : 'lg:sidebar-collapse'">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-4 border-b border-white/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-tz-gold flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-tz-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1h2m10 1l2-1V8a1 1 0 00-1-1h-2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 6h2a2 2 0 012 2v4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="sidebar-logo-text">
                    <p class="text-white font-extrabold text-base leading-tight">Mtokoma</p>
                    <p class="text-white/40 text-[10px] font-semibold uppercase tracking-wider">Motorcycle Parts</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto scrollbar-thin py-3">
            <div class="px-3 space-y-0.5">

                {{-- DASHBOARD --}}
                @can('view_dashboard')
                <a href="{{ route('dashboard') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Dashboard</span>
                </a>
                @endcan

                {{-- OPERATIONS --}}
                <p class="sidebar-section-label">Operations</p>

                @can('access_pos')
                <a href="{{ route('pos.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap font-bold text-tz-gold">Point of Sale</span>
                </a>
                @endcan

                @can('view_sales')
                <a href="{{ route('sales.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Sales</span>
                </a>
                @endcan

                @can('view_purchases')
                <a href="{{ route('purchases.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.183A1.125 1.125 0 0018.056 3H5.106m2.394 11.25L7.5 14.25m0 0h9.75m-9.75 0L6.375 3M20.25 7.5H21"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Purchases</span>
                </a>
                @endcan

                @can('view_expenses')
                <a href="{{ route('expenses.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Expenses</span>
                </a>
                @endcan

                {{-- INVENTORY --}}
                <p class="sidebar-section-label">Inventory</p>

                @can('view_items')
                <a href="{{ route('items.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Items</span>
                </a>
                @endcan

                @can('view_categories')
                <a href="{{ route('categories.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Categories</span>
                </a>
                @endcan

                @can('view_stock')
                <a href="{{ route('stock.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Stock</span>
                </a>
                @endcan

                @can('adjust_stock')
                <a href="{{ route('stock.adjust.form') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('stock.adjust*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Adjustments</span>
                </a>
                @endcan

                @can('create_reconciliations')
                <a href="{{ route('reconciliations.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Reconciliations</span>
                </a>
                @endcan

                {{-- PEOPLE --}}
                <p class="sidebar-section-label">People</p>

                @can('view_customers')
                <a href="{{ route('customers.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Customers</span>
                </a>
                @endcan

                @can('view_suppliers')
                <a href="{{ route('suppliers.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-2.25h7.5m-7.5 0H6.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125H9"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Suppliers</span>
                </a>
                @endcan

                {{-- REPORTS & ANALYTICS --}}
                @can('view_reports')
                <p class="sidebar-section-label">Reports</p>

                <a href="{{ route('reports.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Reports</span>
                </a>
                @endcan

                @can('import_data')
                <a href="{{ route('import-export.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('import-export.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Import / Export</span>
                </a>
                @endcan

                {{-- SYSTEM (Admin only) --}}
                @if(auth()->user()->role && in_array(auth()->user()->role->slug, ['super-admin', 'manager']))
                <p class="sidebar-section-label">System</p>

                @can('view_users')
                <a href="{{ route('users.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Users</span>
                </a>
                @endcan

                @can('create_roles')
                <a href="{{ route('roles.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Roles & Permissions</span>
                </a>
                @endcan

                @can('view_settings')
                <a href="{{ route('settings.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Settings</span>
                </a>
                @endcan

                @can('view_activity')
                <a href="{{ route('activity.index') }}" onclick="showLoading()"
                   class="sidebar-link flex items-center px-3 py-2.5 text-white/70 rounded-lg text-sm
                          {{ request()->routeIs('activity*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-text ml-3 whitespace-nowrap">Activity Log</span>
                </a>
                @endcan
                @endif

            </div>
        </nav>

        {{-- User Info at Bottom --}}
        <div class="flex-shrink-0 border-t border-white/10 p-3">
            <div class="flex items-center">
                <div class="w-9 h-9 rounded-full bg-tz-gold/20 flex items-center justify-center flex-shrink-0">
                    <span class="text-tz-gold text-sm font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                </div>
                <div class="sidebar-text ml-3 flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-white/40 truncate">{{ Auth::user()->role?->name ?? 'Admin' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-text">
                    @csrf
                    <button type="submit" class="text-white/40 hover:text-white transition-colors" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="transition-all duration-300 lg:ml-64" x-bind:class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'">

        {{-- Top Header --}}
        <header class="bg-white border-b-2 border-tz-green h-16 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-tz-dark">{{ $pageTitle ?? '' }} @yield('header-title', '')</h1>
                    @hasSection('breadcrumbs')
                    <nav class="flex items-center text-xs text-gray-400 -mt-0.5">
                        <a href="{{ route('dashboard') }}" onclick="showLoading()" class="hover:text-tz-green transition-colors">Home</a>
                        @yield('breadcrumbs')
                    </nav>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:block relative">
                    <input type="text" placeholder="Search..." class="w-56 pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-tz-green/20 focus:border-tz-green bg-gray-50">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-tz-green flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role?->name ?? 'Admin' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-1 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('users.edit', Auth::user()->id) }}" onclick="showLoading()" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            My Profile
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @include('components.flash-messages')

        {{-- Page Content --}}
        <main class="p-4 lg:p-6 page-content">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white px-4 lg:px-6 py-4">
            <p class="text-sm text-gray-400 text-center">&copy; 2026 Mtokoma Motorcycle Parts. All rights reserved.</p>
        </footer>
    </div>

    {{-- Loading Bar Script --}}
    <script>
        function showLoading() {
            const bar = document.getElementById('loading-bar');
            bar.style.display = 'block';
            bar.style.width = '0%';
            setTimeout(() => bar.style.width = '60%', 10);
            setTimeout(() => bar.style.width = '80%', 800);
            setTimeout(() => bar.style.width = '95%', 2000);
        }
        function hideLoading() {
            const bar = document.getElementById('loading-bar');
            bar.style.width = '100%';
            setTimeout(() => { bar.style.display = 'none'; bar.style.width = '0%'; }, 300);
        }
        window.addEventListener('load', hideLoading);
        window.addEventListener('pageshow', hideLoading);
    </script>

    {{-- Toast Manager Script --}}
    <script>
        function toastManager() {
            return {
                toasts: [],
                counter: 0,
                init() {
                    @if(session('success'))
                        this.add('success', 'Success', '{{ session('success') }}');
                    @endif
                    @if(session('error'))
                        this.add('error', 'Error', '{{ session('error') }}');
                    @endif
                    @if(session('warning'))
                        this.add('warning', 'Warning', '{{ session('warning') }}');
                    @endif
                    @if(session('info'))
                        this.add('info', 'Info', '{{ session('info') }}');
                    @endif
                },
                add(type, title, message, duration = 4) {
                    const id = ++this.counter;
                    this.toasts.push({ id, type, title, message, duration, removing: false });
                    setTimeout(() => this.remove(id), duration * 1000 + 500);
                },
                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) {
                        toast.removing = true;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300);
                    }
                },
                startProgress(toast) {}
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
