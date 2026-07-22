<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mtokoma Motorcycle Parts')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#2490ef', light: '#e8f0fe', dark: '#1a7ad4' },
                        accent: { DEFAULT: '#2490ef', light: '#e8f0fe', dark: '#1a7ad4' },
                        success: { DEFAULT: '#28a745', light: '#e6f4ea', dark: '#1e7e34' },
                        danger: { DEFAULT: '#dc3545', light: '#fce8ea', dark: '#c82333' },
                        warning: { DEFAULT: '#f0ad4e', light: '#fff8e6', dark: '#e09d2f' },
                        body: '#374151',
                        heading: '#1f2937',
                        muted: '#6b7280',
                        border: '#e2e5e9',
                        surface: '#ffffff',
                        canvas: '#f5f7fa',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    fontSize: {
                        'xs': ['0.75rem', { lineHeight: '1rem' }],
                        'sm': ['0.8125rem', { lineHeight: '1.125rem' }],
                        'base': ['0.875rem', { lineHeight: '1.25rem' }],
                        'lg': ['1rem', { lineHeight: '1.5rem' }],
                        'xl': ['1.125rem', { lineHeight: '1.75rem' }],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Base ── */
        body { background: #f5f7fa; color: #374151; font-size: 0.875rem; line-height: 1.5; -webkit-font-smoothing: antialiased; }

        /* ── Sidebar ── */
        .sidebar {
            width: 15rem; background: #ffffff; border-right: 1px solid #e2e5e9;
            transition: width 0.2s ease; display: flex; flex-direction: column;
        }
        .sidebar.collapsed { width: 4rem; }
        .sidebar.collapsed .sidebar-label,
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .sidebar-section-label span,
        .sidebar.collapsed .sidebar-chevron,
        .sidebar.collapsed .sidebar-user-info { display: none; }
        .sidebar.collapsed .sidebar-link { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar.collapsed .sidebar-link .sidebar-icon { margin: 0; }
        .sidebar.collapsed .sidebar-user-section { justify-content: center; }
        .sidebar.collapsed .sidebar-logo-text { display: none; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); z-index: 50; }
            .sidebar.mobile-open { transform: translateX(0); }
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.4375rem 0.75rem; border-radius: 0.375rem;
            font-size: 0.8125rem; font-weight: 500; color: #4b5563;
            transition: all 0.15s ease; text-decoration: none; margin: 0 0.5rem;
        }
        .sidebar-link:hover { background: #f5f7fa; color: #1f2937; }
        .sidebar-link.active { background: #e8f0fe; color: #2490ef; font-weight: 600; }
        .sidebar-link.active .sidebar-icon { background: #2490ef; color: #ffffff; }

        .sidebar-icon {
            width: 2rem; height: 2rem; border-radius: 0.375rem;
            background: #f5f7fa; color: #6b7280;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: all 0.15s ease;
        }
        .sidebar-link.active .sidebar-icon { background: #2490ef; color: #ffffff; }

        .sidebar-section-label {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; padding: 0.75rem 0.75rem 0.25rem;
            font-size: 0.6875rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: #9ca3af; cursor: pointer;
            background: none; border: none; text-align: left;
            transition: color 0.15s ease;
        }
        .sidebar-section-label:hover { color: #6b7280; }

        .sidebar-collapse-btn {
            width: 2rem; height: 2rem; border-radius: 0.375rem;
            display: flex; align-items: center; justify-content: center;
            color: #6b7280; cursor: pointer; transition: all 0.15s ease;
            background: none; border: none;
        }
        .sidebar-collapse-btn:hover { background: #f5f7fa; color: #1f2937; }

        /* ── Scrollbar ── */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

        /* ── Toast ── */
        .toast-wrap {
            position: fixed; top: 1rem; right: 1rem; z-index: 10000;
            display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none;
        }
        .toast-wrap > * { pointer-events: auto; }
        .toast {
            min-width: 20rem; max-width: 24rem;
            background: #ffffff; border: 1px solid #e2e5e9;
            border-radius: 0.5rem; overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            animation: toastIn 0.3s ease forwards;
        }
        .toast.removing { animation: toastOut 0.2s ease forwards; }
        @keyframes toastIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        @keyframes toastOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100%); } }

        /* ── Form Inputs ── */
        input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="tel"], input[type="url"],
        input[type="date"], input[type="time"], input[type="datetime-local"],
        input[type="file"], textarea, select {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.8125rem !important;
            line-height: 1.25rem !important;
            color: #1f2937 !important;
            width: 100% !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #2490ef !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(36,144,239,0.15) !important;
            background-color: #ffffff !important;
        }
        input::placeholder, textarea::placeholder { color: #9ca3af !important; }
        select { appearance: auto !important; background-image: none !important; }
        select option { background: #ffffff; color: #1f2937; }
        textarea { resize: vertical !important; min-height: 5rem; }

        /* Search with icon alignment */
        .search-with-icon { position: relative; }
        .search-with-icon svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .search-with-icon input { padding-left: 2.25rem !important; }

        .form-label {
            display: block; font-size: 0.8125rem; font-weight: 500;
            color: #374151; margin-bottom: 0.25rem;
        }
        .form-label .required { color: #dc3545; }

        .form-group { margin-bottom: 0.875rem; }

        .form-card {
            background: #ffffff; border: 1px solid #e2e5e9;
            border-radius: 0.5rem; padding: 1.25rem;
        }

        .form-error {
            font-size: 0.75rem; color: #dc3545; margin-top: 0.25rem;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.5rem 1rem; border-radius: 0.375rem;
            font-size: 0.8125rem; font-weight: 500;
            cursor: pointer; transition: all 0.15s ease;
            text-decoration: none; white-space: nowrap; border: none;
        }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }
        .btn-primary { background: #2490ef; color: #ffffff; }
        .btn-primary:hover { background: #1a7ad4; }
        .btn-secondary { background: #ffffff; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #f5f7fa; }
        .btn-danger { background: #dc3545; color: #ffffff; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; color: #ffffff; }
        .btn-success:hover { background: #1e7e34; }
        .btn-warning { background: #f0ad4e; color: #ffffff; }
        .btn-warning:hover { background: #e09d2f; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.125rem 0.5rem; border-radius: 9999px;
            font-size: 0.6875rem; font-weight: 600; line-height: 1.25rem;
        }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .badge-green { background: #e6f4ea; color: #1e7e34; }
        .badge-red { background: #fce8ea; color: #c82333; }
        .badge-yellow { background: #fff8e6; color: #e09d2f; }

        /* ── Autocomplete ── */
        .autocomplete-dropdown {
            position: absolute; z-index: 50; width: 100%;
            background: #ffffff; border: 1px solid #d1d5db; border-radius: 0.375rem;
            margin-top: 0.25rem; max-height: 15rem; overflow-y: auto;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .autocomplete-item {
            padding: 0.5rem 0.75rem; font-size: 0.8125rem; cursor: pointer;
            transition: background 0.1s; color: #1f2937;
        }
        .autocomplete-item:hover { background: #f5f7fa; }
        .autocomplete-item + .autocomplete-item { border-top: 1px solid #f3f4f6; }

        /* ── Table ── */
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.8125rem; }
        .data-table th {
            padding: 0.625rem 0.75rem; text-align: left;
            font-weight: 600; font-size: 0.75rem; text-transform: uppercase;
            letter-spacing: 0.05em; color: #6b7280;
            background: #f5f7fa; border-bottom: 1px solid #e2e5e9;
        }
        .data-table td {
            padding: 0.625rem 0.75rem; border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .data-table tbody tr:hover { background: #f5f7fa; }

        /* ── Card ── */
        .card {
            background: #ffffff; border: 1px solid #e2e5e9;
            border-radius: 0.5rem; overflow: hidden;
        }
        .card-header {
            padding: 0.875rem 1rem; border-bottom: 1px solid #e2e5e9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-body { padding: 1rem; }

        /* ── Page Header ── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .page-title {
            font-size: 1.125rem; font-weight: 700; color: #1f2937; line-height: 1.5;
        }
        .page-subtitle { font-size: 0.8125rem; color: #6b7280; margin-top: 0.125rem; }

        /* ── Stat Card ── */
        .stat-card {
            background: #ffffff; border: 1px solid #e2e5e9;
            border-radius: 0.5rem; padding: 1rem;
            display: flex; align-items: flex-start; gap: 0.75rem;
        }
        .stat-icon {
            width: 2.25rem; height: 2.25rem; border-radius: 0.5rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-value { font-size: 1.25rem; font-weight: 700; color: #1f2937; line-height: 1.25; }
        .stat-label { font-size: 0.75rem; color: #6b7280; margin-top: 0.125rem; }

        /* ── Sidebar scroll preservation ── */
        (function(){})();
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenu: false }">

    <div id="loading-bar" class="hidden"></div>

    {{-- Toast --}}
    <div class="toast-wrap" x-data="toastManager()" x-init="init()">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="toast" :class="toast.removing ? 'removing' : ''">
                <div class="flex items-start gap-3 p-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <template x-if="toast.type==='success'">
                            <div class="w-6 h-6 rounded-full bg-success-light flex items-center justify-center">
                                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type==='error'">
                            <div class="w-6 h-6 rounded-full bg-danger-light flex items-center justify-center">
                                <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type==='warning'">
                            <div class="w-6 h-6 rounded-full bg-warning-light flex items-center justify-center">
                                <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126Z"/></svg>
                            </div>
                        </template>
                        <template x-if="toast.type==='info'">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900" x-text="toast.title"></p>
                        <p class="text-xs text-gray-500 mt-0.5" x-text="toast.message" x-show="toast.message"></p>
                    </div>
                    <button @click="remove(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Mobile Overlay --}}
    <div x-show="mobileMenu" x-cloak class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="mobileMenu=false"
         x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-full"
           :class="(mobileMenu ? 'mobile-open' : '') + (sidebarOpen ? '' : ' collapsed')"
           x-init="$watch('mobileMenu', v => { if(!v) document.body.style.overflow = '' }); $watch('sidebarOpen', () => {})">

        {{-- Logo --}}
        <div class="flex items-center h-14 px-4 border-b border-gray-100 flex-shrink-0 gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1h2m10 1l2-1V8a1 1 0 00-1-1h-2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 6h2a2 2 0 012 2v4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="sidebar-label min-w-0">
                <p class="text-sm font-bold text-gray-900 leading-tight">Mtokoma</p>
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Motorcycle Parts</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-2" id="sidebar-nav"
             x-data="{ open: { ops:true, inv:true, ppl:true, rpt:true, adm:true } }">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="sidebar-icon">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
                <span class="sidebar-text">Dashboard</span>
            </a>

            {{-- Operations --}}
            <button @click="open.ops=!open.ops" class="sidebar-section-label">
                <span>Operations</span>
                <svg class="w-3 h-3 sidebar-chevron transition-transform duration-200" :class="open.ops?'':'-rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open.ops" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25Z"/></svg></div>
                    <span class="sidebar-text">Point of Sale</span>
                </a>
                <a href="{{ route('sales.index') }}" class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg></div>
                    <span class="sidebar-text">Sales</span>
                </a>
                <a href="{{ route('purchases.index') }}" class="sidebar-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.183A1.125 1.125 0 0018.056 3H5.106m2.394 11.25L7.5 14.25m0 0h9.75m-9.75 0L6.375 3M20.25 7.5H21"/></svg></div>
                    <span class="sidebar-text">Purchases</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg></div>
                    <span class="sidebar-text">Expenses</span>
                </a>
            </div>

            {{-- Inventory --}}
            <button @click="open.inv=!open.inv" class="sidebar-section-label">
                <span>Inventory</span>
                <svg class="w-3 h-3 sidebar-chevron transition-transform duration-200" :class="open.inv?'':'-rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open.inv" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg></div>
                    <span class="sidebar-text">Items</span>
                </a>
                <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></div>
                    <span class="sidebar-text">Categories</span>
                </a>
                <a href="{{ route('stock.index') }}" class="sidebar-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg></div>
                    <span class="sidebar-text">Stock</span>
                </a>
                <a href="{{ route('stock.adjust.form') }}" class="sidebar-link {{ request()->routeIs('stock.adjust*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg></div>
                    <span class="sidebar-text">Adjustments</span>
                </a>
            </div>

            {{-- People --}}
            <button @click="open.ppl=!open.ppl" class="sidebar-section-label">
                <span>People</span>
                <svg class="w-3 h-3 sidebar-chevron transition-transform duration-200" :class="open.ppl?'':'-rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open.ppl" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg></div>
                    <span class="sidebar-text">Customers</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H18.75m-7.5-2.25h7.5m-7.5 0H6.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125H9"/></svg></div>
                    <span class="sidebar-text">Suppliers</span>
                </a>
                <a href="{{ route('reconciliations.index') }}" class="sidebar-link {{ request()->routeIs('reconciliations.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span class="sidebar-text">Reconciliations</span>
                </a>
            </div>

            {{-- Reports --}}
            <button @click="open.rpt=!open.rpt" class="sidebar-section-label">
                <span>Reports</span>
                <svg class="w-3 h-3 sidebar-chevron transition-transform duration-200" :class="open.rpt?'':'-rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open.rpt" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div>
                    <span class="sidebar-text">Reports</span>
                </a>
                <a href="{{ route('import-export.index') }}" class="sidebar-link {{ request()->routeIs('import-export.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg></div>
                    <span class="sidebar-text">Import / Export</span>
                </a>
            </div>

            {{-- Administration --}}
            <button @click="open.adm=!open.adm" class="sidebar-section-label">
                <span>Administration</span>
                <svg class="w-3 h-3 sidebar-chevron transition-transform duration-200" :class="open.adm?'':'-rotate-90'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div x-show="open.adm" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg></div>
                    <span class="sidebar-text">Users</span>
                </a>
                <a href="{{ route('roles.index') }}" class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
                    <span class="sidebar-text">Roles & Permissions</span>
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <span class="sidebar-text">Settings</span>
                </a>
                <a href="{{ route('activity.index') }}" class="sidebar-link {{ request()->routeIs('activity*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span class="sidebar-text">Activity Log</span>
                </a>
                <a href="{{ route('expense-categories.index') }}" class="sidebar-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg></div>
                    <span class="sidebar-text">Expense Categories</span>
                </a>
                <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <div class="sidebar-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg></div>
                    <span class="sidebar-text">Payments</span>
                </a>
            </div>

        </nav>

        {{-- User Section --}}
        <div class="flex-shrink-0 border-t border-gray-100 p-3 sidebar-user-section">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                </div>
                <div class="sidebar-user-info flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->role?->name ?? 'Admin' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-user-info">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded" title="Sign out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="transition-all duration-200" :class="sidebarOpen ? 'lg:ml-[15rem]' : 'lg:ml-[4rem]'">

        {{-- Header --}}
        <header class="bg-white border-b border-gray-100 h-14 flex items-center justify-between px-4 lg:px-5 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button @click="mobileMenu=!mobileMenu" class="lg:hidden text-gray-500 hover:text-gray-900 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <button @click="sidebarOpen=!sidebarOpen" class="hidden lg:flex sidebar-collapse-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div>
                    <h1 class="text-base font-semibold text-gray-900 leading-tight">{{ $pageTitle ?? '' }} @yield('header-title', '')</h1>
                    <nav class="flex items-center text-[11px] text-gray-400 -mt-0.5">
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-600 transition-colors">Home</a>
                        @hasSection('breadcrumbs')
                            @yield('breadcrumbs')
                        @endif
                    </nav>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open=!open" class="flex items-center gap-2 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors">
                        <div class="w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-white text-xs font-semibold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-900 leading-tight">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-[11px] text-gray-500">{{ Auth::user()->role?->name ?? 'Admin' }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </button>
                    <div x-show="open" @click.away="open=false" x-cloak
                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg py-1 z-50 shadow-lg">
                        <div class="px-3 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('users.edit', Auth::user()->id) }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Profile
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @include('components.flash-messages')

        <main class="p-4 lg:p-5">
            @yield('content')
        </main>

        <footer class="border-t border-gray-100 bg-white px-4 lg:px-5 py-3">
            <p class="text-xs text-gray-400 text-center">&copy; 2026 Mtokoma Motorcycle Parts</p>
        </footer>
    </div>

    <script>
        (function(){
            var KEY='sidebar_scroll_pos',nav=document.getElementById('sidebar-nav');
            if(!nav)return;
            window.addEventListener('beforeunload',function(){sessionStorage.setItem(KEY,nav.scrollTop);});
            var s=sessionStorage.getItem(KEY);
            if(s!==null)requestAnimationFrame(function(){nav.scrollTop=parseInt(s,10);});
        })();
    </script>

    <script>
        function toastManager() {
            return {
                toasts: [], counter: 0,
                init() {
                    @if(session('success')) this.add('success','Success','{{ session('success') }}'); @endif
                    @if(session('error')) this.add('error','Error','{{ session('error') }}'); @endif
                    @if(session('warning')) this.add('warning','Warning','{{ session('warning') }}'); @endif
                    @if(session('info')) this.add('info','Info','{{ session('info') }}'); @endif
                },
                add(type, title, message, duration=4) {
                    var id=++this.counter;
                    this.toasts.push({id,type,title,message,duration,removing:false});
                    setTimeout(()=>this.remove(id),duration*1000+500);
                },
                remove(id) {
                    var t=this.toasts.find(t=>t.id===id);
                    if(t){t.removing=true;setTimeout(()=>{this.toasts=this.toasts.filter(t=>t.id!==id);},200);}
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
