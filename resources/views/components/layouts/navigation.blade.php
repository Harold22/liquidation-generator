<nav class="fixed top-0 right-0 left-0 z-30 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow flex items-center justify-between px-6 transition-all duration-300"
     :class="sidebarOpen ? 'ml-64' : 'ml-20'"
     x-data="updateTime()">

    <!-- Left: Toggle + Title -->
    <div class="flex items-center space-x-4">
        <!-- Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            <svg x-show="!sidebarOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor"    viewBox="0 0 24 24"
                    stroke="currentColor"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    d="M12 6H20M12 12H20M4 18H20M4 6L8 9L4 12"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            <svg x-show="sidebarOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke="currentColor"
                fill="none" xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M12 6H20M12 12H20M4 18H20M8 6L4 9L8 12"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>

        </button>

        <!-- Page Title -->
        <div class="flex items-center justify-start flex-1">
            <div class="text-base font-semibold text-gray-800 dark:text-gray-200">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Right Side: Time + User Dropdown -->
    <div class="flex items-center gap-6">
        <!-- Time -->
        <div class="text-sm text-gray-700 dark:text-gray-300" x-text="currentDateTime"></div>

        <!-- User Dropdown -->
        <div x-data="{ open: false, showLogoutModal: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                <span class="mr-2 font-medium">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-700 border rounded-md shadow-lg z-50">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                <button @click="showLogoutModal = true; open = false"
                        class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-700">Log Out</button>
            </div>

            <!-- Logout Modal -->
            <div x-show="showLogoutModal" x-cloak x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-sm w-full">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Confirm Logout</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                        Are you sure you want to log out?
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button @click="showLogoutModal = false"
                            class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
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
        </div>
    </div>
</nav>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('updateTime', () => ({
            open: false,
            currentDateTime: '',
            updateDateTime() {   
                this.currentDateTime = '';           
                    const now = new Date();
                    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    const timeOptions = { hour: '2-digit', minute: '2-digit' };
                    const dateString = now.toLocaleDateString('en-US', dateOptions);
                    const timeString = now.toLocaleTimeString('en-US', timeOptions);
                    this.currentDateTime = `${dateString} - ${timeString}`;
                    
            },
           init(){
            this.updateDateTime();
            setInterval(() => {
                this.updateDateTime(); 
            }, 1000);
           } 
        
       }));
    });
</script>