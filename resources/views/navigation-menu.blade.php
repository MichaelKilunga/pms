<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->role == 'staff')
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')">
                            {{ __('Sell medicine') }}
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
                            {{ __('Medicine') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('stock') }}" :active="request()->routeIs('stock')">
                            {{ __('Stock') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('staff') }}" :active="request()->routeIs('staff')">
                            {{ __('Staff') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('category') }}" :active="request()->routeIs('category')">
                            {{ __('Categories') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('pharmacies') }}" :active="request()->routeIs('pharmacies')">
                            {{ __('Pharmacies') }}
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
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                {{-- @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        <svg id="Layer_1" class="ms-2 -me-0.5 size-4" data-name="Layer 1"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1500 1500">
                                                <defs>
                                                    <style>
                                                        .cls-1 {
                                                            fill: #ed1c24;
                                                        }
                                        
                                                        .cls-1,
                                                        .cls-2,
                                                        .cls-3 {
                                                            stroke: #231f20;
                                                            stroke-miterlimit: 10;
                                                        }
                                        
                                                        .cls-2 {
                                                            fill: #1c75bc;
                                                        }
                                        
                                                        .cls-3 {
                                                            fill: #009444;
                                                        }
                                                    </style>
                                                </defs>
                                                <path class="cls-1"
                                                    d="M699.92,1165.15q-4.9,5-10,9.61a244.62,244.62,0,0,1-71.48,45,249.38,249.38,0,0,1-94.51,18.57,245.34,245.34,0,0,1-154.23-54.17c-61.93-49.79-93.41-124-91.79-198.12a248.7,248.7,0,0,1,5.69-47.75s0,0,0,0a244.81,244.81,0,0,1,48.41-101l7.63-9.5,25.21-31.34L390,765.07l25.21-31.35,25.2-31.35L465.63,671l25.2-31.35L516,608.32,541.24,577l36.11,30.35a367.51,367.51,0,0,0-77.92,197.2q-1.32,15.53-1.32,31.41,0,10.32.56,20.49a368.88,368.88,0,0,0,8.1,59.34,365.09,365.09,0,0,0,24.36,73A370.76,370.76,0,0,0,699.92,1165.15Z" />
                                                <path class="cls-2"
                                                    d="M1159.42,518.7a247.42,247.42,0,0,1-6.48,47.08,244.3,244.3,0,0,1-7.93,26.61A368.26,368.26,0,0,0,913.36,469.46h0a372.72,372.72,0,0,0-46-2.83q-9.74,0-19.33.5A369.21,369.21,0,0,0,793.73,474a367.64,367.64,0,0,0-126.31,51.43A371.58,371.58,0,0,0,604,577.15l-37.5-31.53,25.2-31.35,25.21-31.36,25.2-31.34,25.21-31.35,25.2-31.36,25.21-31.35,3.2-4a245.54,245.54,0,0,1,23.92-25.76,244.78,244.78,0,0,1,70.39-45.9,249.37,249.37,0,0,1,98.37-20.2,245.31,245.31,0,0,1,154.22,54.17C1131,366.69,1162.52,443,1159.42,518.7Z" />
                                                <path class="cls-3"
                                                    d="M1122.88,592.15,696.62,1142a352.81,352.81,0,0,1-35.4-20l433.52-559.21A355.69,355.69,0,0,1,1122.88,592.15Z" />
                                                <path class="cls-3"
                                                    d="M1077.76,550.14,642.66,1111.4a356.41,356.41,0,0,1-80.52-93.06,352.71,352.71,0,0,1-47.29-135.92,358.15,358.15,0,0,1-3-46.36q0-8,.35-15.86a354,354,0,0,1,13.67-83.26h0a356,356,0,0,1,160-206.46h0a352.59,352.59,0,0,1,138.21-47A357.12,357.12,0,0,1,867,480.89c3.31,0,6.6.05,9.89.14a357.71,357.71,0,0,1,47.43,4.46A352.85,352.85,0,0,1,1044,528.06,356,356,0,0,1,1077.76,550.14Z" />
                                                <path class="cls-3"
                                                    d="M1222.19,836.06a357.1,357.1,0,0,1-4.92,59.25,352.9,352.9,0,0,1-48.58,128.29l0,0A355.78,355.78,0,0,1,1124.2,1081l0,0a355.75,355.75,0,0,1-112.51,79.51h0A353,353,0,0,1,886.5,1190.7q-9.68.52-19.48.53-15.56,0-30.79-1.32A352.81,352.81,0,0,1,749,1171.15,351.66,351.66,0,0,1,710.62,1155l426.26-549.87A353.72,353.72,0,0,1,1222,823.61Q1222.19,829.81,1222.19,836.06Z" />
                                            </svg>
                                        </a>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif --}}

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

                            {{-- @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif --}}

                            {{-- Choose pharmacy --}}
                            @if (Auth::user()->role == 'owner')
                                <x-dropdown-link href="{{ route('pharmacies.switch') }}">
                                    <p class="btn# btn-light text-danger">{{ __('Switch Pharmacy') }} </p>
                                </x-dropdown-link>
                            @endif

                            {{-- @if (Auth::user()->role != 'owner')
                                <x-dropdown-link href="#"> --}}
                            {{-- @foreach ($pharmacy as $pharmacy)
                                        {{$pharmacy->name}}
                                    @endforeach --}}
                            {{-- <p class=" btn-light text-danger disabled">{{ session('pharmacy_name') }}</p>
                                </x-dropdown-link>
                            @endif --}}

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

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
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
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
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
                <x-responsive-nav-link href="{{ route('sales') }}" :active="request()->routeIs('sales')">
                    {{ __('Sell medicine') }}
                </x-responsive-nav-link>
                @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
                    <x-responsive-nav-link href="{{ route('medicines') }}" :active="request()->routeIs('medicines')">
                        {{ __('Medicine') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('stock') }}" :active="request()->routeIs('stock')">
                        {{ __('Stock') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('staff') }}" :active="request()->routeIs('staff')">
                        {{ __('Staff') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('category') }}" :active="request()->routeIs('category')">
                        {{ __('Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('pharmacies') }}" :active="request()->routeIs('pharmacies')">
                        {{ __('Pharmacies') }}
                    </x-responsive-nav-link>
                @endif

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

                {{-- @if (Auth::user()->role != 'owner') --}}
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
                {{-- @endif --}}

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
