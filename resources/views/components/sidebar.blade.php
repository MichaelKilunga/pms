<div>
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-20 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Wrapper -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 z-30 w-64 h-screen overflow-y-auto pt-16 transition-transform duration-300 bg-white border-r border-gray-200 lg:translate-x-0 lg:static# lg:fixed dark:bg-gray-800 dark:border-gray-700">
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @foreach ($menu as $item)
            @if (isset($item['children']))
                {{-- Dropdown Menu --}}
                @php
                    $isActive = false;
                    foreach ($item['children'] as $child) {
                        if (request()->routeIs($child['route'])) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp
                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = ! open"
                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                        {{ $isActive
                            ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 dark:text-primary-400'
                            : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                        <div class="flex items-center gap-3">
                            @if (isset($item['icon']))
                                <i class="{{ $item['icon'] }} text-lg {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                            @endif
                            <span>{{ __($item['label']) }}</span>
                        </div>
                        <svg :class="{ 'rotate-90': open }" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-collapse class="pl-4 space-y-1">
                        @foreach ($item['children'] as $child)
                            @php
                                $childActive = request()->routeIs($child['route']);
                                $routeParams = $child['params'] ?? [];
                            @endphp
                            <a href="{{ route($child['route'], $routeParams) }}"
                                class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                {{ $childActive
                                    ? 'text-primary-600 bg-primary-50/50 dark:text-primary-400 dark:bg-gray-700/50'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/30' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $childActive ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400' }}"></span>
                                {{ __($child['label']) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Single Link --}}
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                    {{ $isActive
                        ? 'bg-primary-50 text-primary-700 dark:bg-gray-700 dark:text-primary-400'
                        : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    @if (isset($item['icon']))
                        <i class="{{ $item['icon'] }} text-lg {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                    @endif
                    <span>{{ __($item['label']) }}</span>
                </a>
            @endif
        @endforeach
    </div>
</aside>
