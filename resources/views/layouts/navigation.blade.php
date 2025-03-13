<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
           <!-- Logo and System Name -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logos/dswd-logo-big.png') }}" 
                        alt="Logo" 
                        class="h-9 w-auto max-w-[150px] object-contain">
                </a>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Liquidation Generator
                </span>
            </div>


            <div class="hidden sm:flex items-center ml-auto space-x-8">
                <!-- Desktop Navigation Links -->
                <div class="flex space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cash-advance.add')" :active="request()->routeIs('cash-advance.add')">
                        {{ __('Add Cash Advance') }}
                    </x-nav-link>
                    <x-nav-link :href="route('import-files')" :active="request()->routeIs('import-files')">
                        {{ __('Import Files') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cash-advance.list')" :active="request()->routeIs('cash-advance.list')">
                        {{ __('List of Cash Advance') }}
                    </x-nav-link>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <span class="mr-2 text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link class="font-medium" :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link class="font-medium" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            <!-- Hamburger Button for Mobile -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="p-2 rounded-md text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cash-advance.add')" :active="request()->routeIs('cash-advance.add')">
                {{ __('Add Cash Advance') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('import-files')" :active="request()->routeIs('import-files')">
                {{ __('Import Files') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cash-advance.list')" :active="request()->routeIs('cash-advance.list')">
                {{ __('List of Cash Advance') }}
            </x-responsive-nav-link>
        </div>

        <!-- User Dropdown (Mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
