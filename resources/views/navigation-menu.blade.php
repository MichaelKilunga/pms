<nav
    class="fixed left-0 top-0 z-40 w-full border-b border-gray-100 bg-white/80 backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/90">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex items-center">
                <!-- Mobile Hamburger -->
                <div class="-ml-2 mr-2 flex items-center lg:hidden">
                    <button @click="sidebarOpen = ! sidebarOpen"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex"
                                d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" />
                            <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden"
                                d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a class="flex items-center gap-2 text-xl font-bold tracking-tight text-primary-600 dark:text-primary-400"
                        href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                        <span class="hidden md:block">PILLPOINTONE</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Clock & Notifications (Simplified) --}}
                <div class="hidden items-center gap-3 md:flex">
                    <p class="font-mono text-sm font-medium text-gray-500 dark:text-gray-400" id="clock"></p>
                </div>

                <!-- Notification Bell -->
                <a class="relative rounded-full p-2 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                    href="{{ route('notifications') }}">
                    <i class="bi bi-bell text-xl"></i>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <div
                            class="absolute -right-1 -top-1 inline-flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-red-500 text-xs font-bold text-white dark:border-gray-900">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </div>
                    @endif
                </a>

                <!-- Settings Dropdown -->
                <div class="relative ml-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-300"
                                type="button">
                                <div class="hidden text-right md:block">
                                    <div class="text-xs text-gray-400">Welcome back</div>
                                    <div class="font-bold text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                                </div>
                                <img alt="{{ Auth::user()->name }}"
                                    class="h-8 w-8 rounded-full border border-gray-200 object-cover"
                                    src="{{ Auth::user()->profile_photo_url }}" />
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <a class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </a>

                            @if (Auth::user()->hasRole('Superadmin'))
                                <a class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                    href="{{ route('admin.settings.system') }}">
                                    {{ __('System Configuration') }}
                                </a>
                            @endif
                            @if (Auth::user()->hasRole('Owner'))
                                <a class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                    href="{{ route('pharmacies.switch') }}">
                                    <span class="text-red-500">{{ __('Switch Pharmacy') }}</span>
                                </a>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form action="{{ route('logout') }}" method="POST" x-data>
                                @csrf
                                <a @click.prevent="$root.submit();"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                                    href="{{ route('logout') }}">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

</nav>
