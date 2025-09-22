<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SOE COFIG --}}
    <title>@yield('title') | Pharmacy Management System</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="author" content="SKYLINK SOLUTIONS">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- styles --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>

    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        .chosen-container .chosen-single {
            height: calc(2.5rem + 2px);
            /* Match Bootstrap's default input height */
            line-height: calc(2.5rem + 2px);
            /* border: 1px solid #ced4da; */
            border-radius: 0;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            color: black;
            background-color: white;
            /* background-clip: padding-box; */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Loader Overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            /* Slightly transparent background */
            display: none;
            /* Initially hidden */
            z-index: 9999;

            /* Flexbox to center the spinner */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body class="font-sans antialiased">

    {{-- <x-banner /> --}}
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <div class="flex">

            <!-- SIDEBAR (visible only on large screens) -->
            <aside class="hidden lg:flex lg:flex-col w-60 bg-white dark:bg-gray-800 border-r dark:border-gray-700">
                <div class="p-4 min-h-screen overflow-y-auto">
                    {{-- STAFF --}}
                    @if (Auth::user()->role === 'staff')
                        @php
                            $salesOpen = request()->routeIs('sales', 'salesReturns', 'salesNotes');
                            $stockOpen = request()->routeIs('stocks.balance');
                            $reportsOpen = request()->routeIs('notifications', 'agent.messages');
                            $expensesOpen = request()->routeIs('expenses.index', 'expenses.create', 'expenses.edit');
                        @endphp

                        <nav class="space-y-3">
                            {{-- Dashboard --}}
                            <div class="w-full">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-3 py-2 rounded no-underline {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __('Dashboard') }}
                                </a>
                            </div>

                            {{-- Sales group --}}
                            <div x-data="{ open: @json($salesOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Sales</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 space-y-1 mt-1">
                                    <a href="{{ route('sales') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('sales') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Sell medicine') }}
                                    </a>

                                    <a href="{{ route('salesReturns') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('salesReturns') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Sales Returns') }}
                                    </a>

                                    <a href="{{ route('salesNotes') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('salesNotes') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Document Sales') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Stock group --}}
                            <div x-data="{ open: @json($stockOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Stock</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 space-y-1 mt-1">
                                    <a href="{{ route('stocks.balance') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('stocks.balance') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Stock Balance') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Reports group --}}
                            <div x-data="{ open: @json($reportsOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Notifications</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 space-y-1 mt-1">
                                    <a href="{{ route('notifications') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('notifications') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Notifications') }}
                                    </a>

                                    <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.messages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Messages') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Expenses --}}
                            <div x-data="{ open: @json($expensesOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Expenses</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('expenses.index') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.index') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('All Expenses') }}
                                    </a>
                                 
                                    {{-- expenses categories --}}
                                    {{-- <a href="{{ route('expenses.category') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.category') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Categories') }}
                                    </a> --}}
                                    {{-- add vendor --}}
                                    {{-- <a href="{{ route('expenses.vendors') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.vendors') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Vendors') }}
                                    </a> --}}


                                </div>
                            </div>

                        </nav>
                    @endif

                    {{-- OWNER / ADMIN --}}
                    @if (Auth::user()->role === 'owner' || Auth::user()->role === 'admin')
                        @php
                            $salesOpen = request()->routeIs('sales', 'salesReturns', 'salesNotes');
                            $invOpen = request()->routeIs('medicines', 'stock', 'stocks.balance', 'stockTransfers');
                            $manageOpen = request()->routeIs('staff', 'category', 'pharmacies', 'myContracts');
                            $reportsOpen = request()->routeIs('reports.all', 'notifications', 'agent.messages');
                            $expensesOpen = request()->routeIs('expenses.index', 'expenses.create', 'expenses.edit','expenses.vendors','expenses.category');
                        @endphp

                        <nav class="space-y-3">
                            {{-- Dashboard --}}
                            <div class="w-full">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-3 py-2 rounded no-underline {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                    {{ __('Dashboard') }}
                                </a>
                            </div>

                            {{-- Sales --}}
                            <div x-data="{ open: @json($salesOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Sales</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('sales') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('sales') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Sell medicine') }}
                                    </a>
                                    <a href="{{ route('salesReturns') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('salesReturns') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Sales Returns') }}
                                    </a>
                                    <a href="{{ route('salesNotes') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('salesNotes') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Documented Sales') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Stock --}}
                            <div x-data="{ open: @json($invOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Stock</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('medicines') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('medicines') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('All medicine') }}
                                    </a>
                                    <a href="{{ route('stock') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('stock') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Stock') }}
                                    </a>
                                    <a href="{{ route('stocks.balance') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('stocks.balance') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Stock Balance') }}
                                    </a>
                                    <a href="{{ route('stockTransfers') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('stockTransfers') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Stock Transfers') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Management --}}
                            <div x-data="{ open: @json($manageOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Admin</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('staff') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('staff') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Pharmacist') }}
                                    </a>
                                    <a href="{{ route('category') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('category') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Category') }}
                                    </a>
                                    <a href="{{ route('pharmacies') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('pharmacies') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Pharmacies') }}
                                    </a>
                                    <a href="{{ route('myContracts') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('myContracts') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Contracts') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Reports --}}
                            <div x-data="{ open: @json($reportsOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Reports</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('reports.all') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('reports.all') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Reports') }}
                                    </a>
                                    <a href="{{ route('notifications') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('notifications') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Notifications') }}
                                    </a>
                                    <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.messages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Messages') }}
                                    </a>
                                </div>
                            </div>

                              {{-- Expenses --}}
                            <div x-data="{ open: @json($expensesOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Expenses</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('expenses.index') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.index') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('All Expenses') }}
                                    </a>
                                 
                                    {{-- expenses categories --}}
                                    <a href="{{ route('expenses.category') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.category') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Categories') }}
                                    </a>
                                    {{-- add vendor --}}
                                    <a href="{{ route('expenses.vendors') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('expenses.vendors') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Vendors') }}
                                    </a>


                                </div>
                            </div>


                        </nav>
                    @endif

                    {{-- SUPER --}}
                    @if (Auth::user()->role === 'super')
                        @php
                            $systemOpen = request()->routeIs(
                                'dashboard',
                                'superadmin.users',
                                'superadmin.pharmacies',
                                'packages',
                                'allMedicines.all',
                                'notifications',
                                'reports.all',
                                'contracts',
                                'agent.packages',
                                'update.contracts',
                                'agent.pharmacies',
                                'agent.messages',
                                'agent.completeRegistration',
                                'audits.index',
                            );
                        @endphp

                        <nav class="space-y-3">
                            <div x-data="{ open: @json($systemOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">System</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a href="{{ route('superadmin.users') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('superadmin.users') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('System Users') }}
                                    </a>
                                    <a href="{{ route('superadmin.pharmacies') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('superadmin.pharmacies') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Pharmacies') }}
                                    </a>

                                    <a href="{{ route('packages') }}" :active="request()->routeIs('packages')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('packages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Packages') }}
                                    </a>

                                    <a href="{{ route('allMedicines.all') }}"
                                        :active="request()->routeIs('allMedicines.all')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('allMedicines.all') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('All medicines') }}
                                    </a>

                                    <a href="{{ route('notifications') }}"
                                        :active="request()->routeIs('notifications')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('notifications') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Notifications') }}
                                    </a>

                                    {{-- <a href="{{ route('reports.all') }}" :active="request()->routeIs('reports.all')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('reports.all') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Reports') }}
                                    </a> --}}

                                    <a href="{{ route('contracts') }}" :active="request()->routeIs('contracts')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('contracts') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Contracts') }}
                                    </a>

                                    <a href="{{ route('agent.packages', ['action' => 'index']) }}"
                                        :active="request()->routeIs('agent.packages')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.packages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __("Agent's Contracts") }}
                                    </a>

                                    <a href="{{ route('update.contracts') }}"
                                        :active="request()->routeIs('update.contracts')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('update.contracts') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Schedules') }}
                                    </a>

                                    <a href="{{ route('agent.pharmacies', ['action' => 'index']) }}"
                                        :active="request()->routeIs('agent.pharmacies')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.pharmacies') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Pharmacies') }}
                                    </a>

                                    <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                                        :active="request()->routeIs('agent.messages')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.messages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Messages') }}
                                    </a>

                                    <a href="{{ route('agent.completeRegistration', ['action' => 'index']) }}"
                                        :active="request()->routeIs('agent.completeRegistration')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.completeRegistration') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Agents Registration') }}
                                    </a>

                                    <a href="{{ route('audits.index') }}"
                                        :active="request()->routeIs('audits.index')"
                                        class=" block px-3 py-2 rounded no-underline {{ request()->routeIs('audits.index') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Activities') }}
                                    </a>

                                    {{-- ...other super links (same pattern) --}}
                                </div>
                            </div>
                        </nav>
                    @endif

                    {{-- AGENT --}}
                    @if (Auth::user()->role === 'agent')
                        @php
                            $agentOpen = request()->routeIs(
                                'dashboard',
                                'agent.pharmacies',
                                'agent.packages',
                                'agent.messages',
                                'agent.completeRegistration',
                                'agent.contracts',
                            );
                        @endphp

                        <nav class="space-y-3">
                            <div x-data="{ open: @json($agentOpen) }" class="space-y-1">
                                <button @click="open = ! open"
                                    class="w-full flex items-center justify-between px-3 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                    <span class="font-medium">Agent</span>
                                    <svg :class="{ 'rotate-90': open }" class="w-4 h-4 transform transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-transition class="pl-4 mt-1 space-y-1">
                                    <a href="{{ route('dashboard') }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a href="{{ route('agent.pharmacies', ['action' => 'index']) }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.pharmacies') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Pharmacies') }}
                                    </a>
                                    <a href="{{ route('agent.packages', ['action' => 'index']) }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.packages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Packages') }}
                                    </a>
                                    <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                                        class="block px-3 py-2 rounded no-underline {{ request()->routeIs('agent.messages') ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        {{ __('Messages') }}
                                    </a>
                                </div>
                            </div>
                        </nav>
                    @endif
                </div>
            </aside>


            <main class="flex-1 p-6">
                {{ $slot }}
            </main>

            <!-- Loader Overlay -->
            <div id="loader-overlay" class="loader-overlay">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <audio id="notification-sound" src="{{ asset('audio/notify.mp3') }}" preload="auto"></audio>
        </div>
    </div>

    @stack('modals')

    @livewireScripts

    <script>
        $(document).ready(function() {
            var isNotificationCheck = true;
            // requestNotificationPermission();

            // Automatically show the modal if no pharmacy is selected
            @if (!session('current_pharmacy_id'))
                $('#pharmacyModal').modal('show');
            @endif
            @if (session('guest-owner'))
                $('#guestPharmacyModal').modal('show');
            @endif

            // @if (Auth::user()->contracts->where('is_current_contract', 1)->count() < 1)
            //     // $('#guestPharmacyModal').modal('show');
            //     return redirect()->route('myContracts')->with('error','Subscribe first to continue using our products!');
            // @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    // timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    // timer: 2000
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: '{{ session('info') }}',
                    // timer: 2000
                });
            @endif

            $(document).ready(function() {
                $('#Table')
                    .DataTable({
                        paging: true, // Enable paging
                        searching: true, // Enable search bar
                        ordering: true, // Enable column sorting
                        info: false // Enable information display

                    })
                    .columns.adjust()
                    .responsive.recalc();

                $(".onReport").select2({
                    width: "100%",
                    minimumResultsForSearch: 5,
                });

                @if (!session('success') && !session('error') && !session('info'))
                    $("select").not("#conversationRecipients").each(function() {
                        let $select = $(this);
                        let $modal = $select.closest(".modal"); // Check if inside a modal
                        $select.select2({
                            width: "100%",
                            minimumResultsForSearch: 5,
                            dropdownParent: $modal.length ? $modal : $(
                                "body") // Use modal if inside one
                        });

                        // Auto-focus the search input when dropdown opens
                        $select.on("select2:open", function() {
                            document.querySelector(
                                    ".select2-container--open .select2-search__field")
                                .focus();
                        });

                    });
                @endif
            });

            //LOADER
            $(document).ready(function() {
                $('#loader-overlay').hide(); // Hide loader initially


                $('button').on('click', function(event) {
                    const $this = $(this);
                    if ($this.is('#hamburger')) {
                        return;
                    }
                    if ($this.is('#apply-filter-btn')) {
                        return;
                    }
                    // if ($this.is('#forAdminImport')) {
                    //     return;
                    // }

                    //below implement code to check if the button is a submit button and if any of the required field is empty the loader will not appear otherwise it will appear while the form is being submitted
                    if ($this.is('button[type="submit"]')) {
                        const $form = $this.closest('form');
                        if ($form.length) {
                            if ($form[0].checkValidity()) {
                                // $('#loader-overlay').show();
                                $this.addClass(
                                        'bg-light border border-danger# text-danger text-muted')
                                    .css('pointer-events', 'none')
                                    .html(
                                        '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                                    );
                            }
                        }
                    }

                    if (!$this.find('input[name="requiredField"]').val()) {
                        return; // Exit the function
                    }

                    if ($this.is('button.btn-close[data-bs-dismiss="modal"]')) {
                        return; // Exit the function
                    }
                    if ($this.is('.reportsDownloadButton')) {
                        return;
                    }
                    if ($this.is('#addSaleRow')) {
                        return;
                    }
                    if ($this.is('#addStockBtn')) {
                        return;
                    }
                    $this.addClass('bg-light border border-danger# text-danger text-muted')
                        .css('pointer-events', 'none')
                        .html(
                            '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                        );
                });


                // Show the loader before a new page is requested (e.g., on link click)
                $('a').on('click', function(event) {
                    const $this = $(this);

                    // Prevent loader for modal triggers
                    if ($this.attr('data-bs-toggle') === 'modal') {
                        return; // Exit if the link is for opening a modal
                    }

                    // if the url points to open an asset file return
                    if ($this.attr('href').includes('storage/')) {
                        return;
                    }

                    // Disable the link/button
                    // $this.addClass('bg-light border border-danger text-danger text-muted')
                    //     .css('pointer-events', 'none')
                    //     .append(
                    //         '<span class="spinner-border" style="width: 1rem; height: 1rem;" role="status" aria-hidden="true"></span>'
                    //     );


                    // Skip showing loader if the target is a select field (including chosen dropdown)
                    if ($(this).closest('select, .chosen-container').length) {
                        return; // Exit if the link is associated with a select or chosen field
                    }


                    $('#loader-overlay').show();
                });

                // Show the loader when an AJAX request starts
                $(document).ajaxStart(function() {
                    if (!isNotificationCheck) { // Only show loader if it's not a notification check
                        $('#loader-overlay').show();
                    }
                });

                // Hide the loader when the AJAX request completes
                $(document).ajaxStop(function() {
                    $('#loader-overlay').hide();
                });

                // Hide the loader when the page is fully loaded
                $(window).on('load', function() {
                    $('#loader-overlay').hide();
                });

                // Hide the loader if a modal is opened
                $('[data-bs-toggle="modal"]').on('click', function() {
                    $('#loader-overlay').hide();
                });
            });
        });

        //NOTIFICATION RING
        $(document).ready(function() {
            // isNotificationCheck = false;

            $('#loader-overlay').hide(); // Hide loader initially

            function checkNotifications() {

                isNotificationCheck = false;

                $.ajax({
                    url: '/notifications/unread_count', // Replace with your route to fetch unread notifications count
                    method: 'GET',
                    success: function(response) {
                        $('#notifyBell').text(response.unreadCount);
                        $('#notifyBellPhone').text(response.unreadCount);
                        if (response.unreadCount > 0) {
                            // Play the notification sound
                            $('#notification-sound')[0].play();
                            // showBrowserNotification(
                            //     `You have ${response.unreadCount} unread notifications.`);
                        }
                    },
                    complete: function() {
                        isNotificationCheck = true; // Reset the flag after the request completes
                    },
                    error: function() {
                        console.error('Failed to fetch unread notifications.');
                    }
                });
            }

            // Check notifications every minute
            setInterval(checkNotifications, 15000); // 60000 ms = 1 minute
        });

        //SUMMERNOTE
        $(document).ready(function() {
            // if (true) {
            //     $('#printerModal').modal('show');
            // }
            $('.summernote').summernote({
                height: 90, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: true // set focus to editable area after initializing summernote
            });
        });
    </script>

    {{-- CLOCK TIMER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the clock with server time
            let serverTime = new Date('{{ Date::now() }}');

            // Function to update the clock
            function updateClock() {
                const clockElement = document.getElementById('clock');
                clockElement.textContent = serverTime.toLocaleString(); // Display date and time
                serverTime.setSeconds(serverTime.getSeconds() + 1); // Increment by 1 second
            }

            // Update the clock every second
            setInterval(updateClock, 1000);

            // Initial call to display the time immediately
            updateClock();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    {{-- FOR DOWNLOADING REPORTS --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>

    <!-- Include JSZip and pdfmake for export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>

    <!-- Include Buttons extensions -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- include summernote css/js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
