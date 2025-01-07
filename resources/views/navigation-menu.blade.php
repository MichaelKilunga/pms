<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- FIRST ROW --}}
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <p id="clock"
                                        class="inline-flex items-center px-3 mx-2# py-2  text-sm leading-4 font-medium rounded-md text-gray-500 text-primary dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                    </p>
                                    <a href="{{ route('notifications') }}"
                                        class="inline-flex items-center px-2# mx-2# py-2# text-sm leading-4 font-medium rounded-md text-gray-500 text-primary dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 position-relative">
                                        <i class="bi bi-bell fs-4"></i>
                                        {{-- @if (Auth::user()->unreadNotifications->count() > 0) --}}
                                        <sup id="notifyBell"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </sup>
                                        {{-- @endif --}}
                                    </a>
                                    <h4
                                        class="inline-flex items-center px-3 mx-2# py-2  text-sm leading-4 font-medium rounded-md text-gray-500 text-primary dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ session('pharmacy_name') }}
                                        @if (Auth::user()->role == 'super')
                                            SUPER ADMIN
                                        @endif
                                    </h4>
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            {{-- Choose pharmacy --}}
                            @if (Auth::user()->role == 'owner')
                                <x-dropdown-link href="{{ route('pharmacies.switch') }}">
                                    <p class="btn# btn-light text-danger">{{ __('Switch Pharmacy') }} </p>
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{-- NOTIFY BELL --}}
            <div class="-me-2 flex items-center sm:hidden">
                <a href="{{ route('notifications') }}"
                    class="inline-flex items-center mx-2 px-2# mx-2# py-2# text-sm leading-4 font-medium rounded-md text-gray-500 text-primary dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 position-relative">
                    <i class="bi bi-bell fs-4"></i>
                    {{-- @if (Auth::user()->unreadNotifications->count() > 0) --}}
                    <sup id="notifyBellPhone"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </sup>
                    {{-- @endif --}}
                </a>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" id="hamburger"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <hr>
        {{-- SECOND ROW --}}
        {{-- <span class="flex justify-between h-16"> --}}

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex h-16">
                @if (Auth::user()->role == 'staff')
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')">
                        {{ __('Sell medicine') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('notifications') }}" :active="request()->routeIs('notifications')">
                        {{ __('Notifications') }}
                    </x-nav-link>
                @endif
                @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')">
                        {{ __('Sell medicine') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('medicines') }}" :active="request()->routeIs('medicines')">
                        {{ __('All medicine') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('stock') }}" :active="request()->routeIs('stock')">
                        {{ __('Stock') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('staff') }}" :active="request()->routeIs('staff')">
                        {{ __('Staff') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('category') }}" :active="request()->routeIs('category')">
                        {{ __('Category') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('pharmacies') }}" :active="request()->routeIs('pharmacies')">
                        {{ __('Pharmacies') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('notifications') }}" :active="request()->routeIs('notifications')">
                        {{ __('Notifications') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('reports.all') }}" :active="request()->routeIs('reports.all')">
                        {{ __('Reports') }}
                    </x-nav-link>
                @endif
                @if (Auth::user()->role == 'super')
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('superadmin.users') }}" :active="request()->routeIs('superadmin.users')">
                        {{ __('System Users') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('superadmin.pharmacies') }}" :active="request()->routeIs('superadmin.pharmacies')">
                        {{ __('Pharmacies') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                        {{ __('Packages') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('allMedicines.all') }}" :active="request()->routeIs('allMedicines.all')">
                        {{ __('All medicines') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('notifications') }}" :active="request()->routeIs('notifications')">
                        {{ __('Notifications') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('reports.all') }}" :active="request()->routeIs('reports.all')">
                        {{ __('Reports') }}
                    </x-nav-link>
                @endif
            </div>
        {{-- </span> --}}
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')">
                {{ __('Sell medicine') }}
            </x-responsive-nav-link>
            @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
                <x-responsive-nav-link href="{{ route('medicines') }}" :active="request()->routeIs('medicines')">
                    {{ __('All medicine') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('stock') }}" :active="request()->routeIs('stock')">
                    {{ __('Stock') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('staff') }}" :active="request()->routeIs('staff')">
                    {{ __('Staff') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('category') }}" :active="request()->routeIs('category')">
                    {{ __('Category') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('pharmacies') }}" :active="request()->routeIs('pharmacies')">
                    {{ __('Pharmacies') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('reports.all') }}" :active="request()->routeIs('reports.all')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                {{ __('API Tokens') }}
                </x-responsive-nav-link>
                @endif --}}


                {{-- Choose pharmacy --}}
                @if (Auth::user()->role == 'owner')
                    <x-dropdown-link href="{{ route('pharmacies.switch') }}">
                        <p class="btn btn-light text-danger">{{ __('Switch Pharmacy') }} </p>
                    </x-dropdown-link>
                @endif

                @if (Auth::user()->role != 'owner')
                    <x-dropdown-link href="#">
                        {{-- <h4
                            class="inline-flex items-center px-3 mx-2# py-2  text-sm leading-4 font-medium rounded-md text-gray-500 text-primary dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                            {{ session('pharmacy_name') }}</h4>
                    <button type="button" --}}
                        {{-- @foreach ($pharmacy as $pharmacy)
                                        {{$pharmacy->name}}
                        @endforeach --}}

                        <p class=" btn-light text-danger disabled">{{ session('pharmacy_name') }}</p>
                    </x-dropdown-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
