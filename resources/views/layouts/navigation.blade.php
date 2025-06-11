<nav x-data="{ open: false, showLogoutModal: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logos/dswd-logo-big.png') }}" alt="Logo" class="h-9 w-auto max-w-[150px] object-contain">
                </a>
                <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Liquidation Generator</span>
            </div>

         <!-- Desktop Menu -->
            <div class="hidden sm:flex items-center space-x-8 ml-auto">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('cash-advance.add')" :active="request()->routeIs('cash-advance.add')">Add Cash Advance</x-nav-link>
                <x-nav-link :href="route('import-files')" :active="request()->routeIs('import-files')">Import Files</x-nav-link>
                <x-nav-link :href="route('cash-advance.list')" :active="request()->routeIs('cash-advance.list')">List of Cash Advance</x-nav-link>

                @role('Admin')
                    <!-- Admin Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <span class="mr-2 text-sm font-medium">Admin</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('users')" :active="request()->routeIs('users')">Users</x-dropdown-link>
                            <x-dropdown-link :href="route('logs')" :active="request()->routeIs('logs')">Logs</x-dropdown-link>
                            <x-dropdown-link :href="route('sdo')" :active="request()->routeIs('sdo')" >SDO Management</x-dropdown-link> <!-- Add your actual route name here -->
                            <x-dropdown-link :href="route('offices')" :active="request()->routeIs('offices')" >Offices</x-dropdown-link> <!-- Add your actual route name here -->
                        </x-slot>
                    </x-dropdown>
                @endrole

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <span class="mr-2 text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <x-dropdown-link href="#" @click.prevent="showLogoutModal = true">Log Out</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

   <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cash-advance.add')" :active="request()->routeIs('cash-advance.add')">Add Cash Advance</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('import-files')" :active="request()->routeIs('import-files')">Import Files</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cash-advance.list')" :active="request()->routeIs('cash-advance.list')">List of Cash Advance</x-responsive-nav-link>

            @role('Admin')
                <div class="px-4 pt-3 text-sm font-semibold text-gray-500 dark:text-gray-400">Admin</div>
                <x-responsive-nav-link :href="route('users')" :active="request()->routeIs('users')">Users</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('logs')" :active="request()->routeIs('logs')">Logs</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('sdo')" :active="request()->routeIs('sdo')">SDOs</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('offices')" :active="request()->routeIs('offices')">Offices</x-responsive-nav-link>
            @endrole
        </div>

        <!-- User Info -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <x-responsive-nav-link href="#" @click.prevent="showLogoutModal = true">Log Out</x-responsive-nav-link>
            </div>
        </div>
    </div>


    <!-- Logout Confirmation Modal -->
    <div x-show="showLogoutModal" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Confirm Logout</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                Are you sure you want to log out?
            </p>
            <div class="flex justify-end space-x-3">
                <button @click="showLogoutModal = false"
                    class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded">
                    Cancel
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
