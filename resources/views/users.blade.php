<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div x-data="users()" class="py-8">
        <div x-show="loading">
            <x-spinner />
        </div> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            @include('error-messages.messages')
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                <!-- User Registration Form -->
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-700 p-6 rounded-lg shadow self-start">
                    <h3 class="text-lg font-semibold mb-4 text-blue-500">Register New User</h3>
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                         <!-- Name -->
                        <div>
                            <div class="flex">
                                <x-input-label for="name" :value="__('Name')" />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <div class="flex">
                                <x-input-label for="email" :value="__('Email')" />
                                <span class="text-red-500">*</span>
                            </div>
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>

                        <!-- Password -->
                        <div class="mt-4" x-data="{ showPassword: false }">
                            <div class="flex">
                                <x-input-label for="password" :value="__('Password')" />
                                <span class="text-red-500">*</span>
                            </div>

                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    required
                                    class="block mt-1 w-full pr-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />

                                <button type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <!-- Show eye -->
                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Hide eye -->
                                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.975 9.975 0 012.378-3.568m3.236-2.06A9.99 9.99 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.976 9.976 0 01-4.122 5.152M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4" x-data="{ showConfirmPassword: false }">
                            <div class="flex">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <span class="text-red-500">*</span>
                            </div>

                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    class="block mt-1 w-full pr-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />

                                <button type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <!-- Show eye -->
                                    <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Hide eye -->
                                    <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.975 9.975 0 012.378-3.568m3.236-2.06A9.99 9.99 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.976 9.976 0 01-4.122 5.152M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3" />
                                    </svg>
                                </button>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">

                            <x-primary-button class="w-full flex justify-center">
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- User List -->
                <div class="w-full lg:w-2/3 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-blue-500">List of Users</h3>
                            <input 
                                type="text" 
                                placeholder="Search..." 
                                x-model="searchUser"
                                @input.debounce.500ms="getUsers"
                                class="px-4 py-1.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 
                                    dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-100 w-48"
                            />
                        </div>

                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300 border rounded-lg">
                            <thead class="bg-gray-100 dark:bg-gray-600">
                                <tr class="border">
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2">Roles</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="user in users" :key="user.id">
                                    <tr class="border-b">
                                        <td class="px-4 py-2" x-text="user.name"></td>
                                        <td class="px-4 py-2" x-text="user.email"></td>
                                        <td class="px-4 py-2">
                                            <template x-for="role in user.roles" :key="role.id">
                                                <span class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded" x-text="role.name"></span>
                                            </template>
                                        </td>
                                        <td :class="user.is_active ? 'text-green-500' : 'text-red-500'" class="px-4 py-2" x-text="user.is_active ? 'Activated' : 'Not Activated'"></td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <div x-data="{ tooltipEdit: false, tooltipDelete: false, tooltipReset: false}" class="relative inline-flex space-x-2 text-left">
                                                    <!-- Edit Button -->
                                                    <div class="relative">
                                                        <button @click="editUser(user)"
                                                            @mouseenter="tooltipEdit = true" 
                                                            @mouseleave="tooltipEdit = false" 
                                                            class="py-2 pr-2 text-yellow-400 hover:text-yellow-600 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                            </svg>

                                                        </button>
                                                        <span x-show="tooltipEdit"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Update
                                                        </span>
                                                    </div>

                                                    <!-- Reset Password button -->
                                                    <div class="relative">
                                                        <button @click="confirmDeleteUser(user)"
                                                            @mouseenter="tooltipReset = true" 
                                                            @mouseleave="tooltipReset = false" 
                                                            class="p-2 text-gray-500 hover:text-green-600 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                                            </svg>

                                                        </button>
                                                        <span x-show="tooltipReset"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Reset Password
                                                        </span>
                                                    </div>
                                                    <!-- Delete Button -->
                                                    <div class="relative">
                                                        <button @click="confirmDeleteUser(user)"
                                                            @mouseenter="tooltipDelete = true" 
                                                            @mouseleave="tooltipDelete = false" 
                                                            class="p-2 text-gray-500 hover:text-red-600 focus:outline-none transition duration-200 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>

                                                        </button>
                                                        <span x-show="tooltipDelete"
                                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-md whitespace-nowrap"
                                                            x-transition.opacity>
                                                            Delete
                                                        </span>
                                                    </div>
                                                   

                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                </template>
                            </tbody>
                            <!-- modal for update -->
                             <div x-show="updateUserModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                    <header class="flex justify-between items-center">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update User</h2>
                                        <button @click="updateUserModal = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>
                                    @include('users.update-user-form')
                                </div>
                            </div>
                              <!-- Delete Confirmation Modal -->
                            <div x-show="deleteUserModal" x-cloak x-transition class="fixed inset-0 w-screen h-full z-[999] flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                                    <header class="flex justify-between items-center mb-4">
                                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirm Deletion</h2>
                                        <button @click="deleteUserModal = false" 
                                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </header>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                                        Are you sure you want to delete user <strong x-text="userToDelete?.name"></strong>?
                                    </p>
                                    <div class="flex justify-end space-x-3">
                                        <button @click="deleteUserModal = false" class="px-4 py-2 bg-gray-200 rounded text-sm">Cancel</button>
                                        <button @click="deleteConfirmed" class="px-4 py-2 bg-red-600 text-white rounded text-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </table>

                        <!-- Pagination -->
                        <div class="flex items-center justify-center mt-6">
                            <div class="space-x-2">
                                <button @click="changePageUser(userCurrentPage - 1)" :disabled="userCurrentPage === 1"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    &laquo; Prev
                                </button>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Page <span x-text="userCurrentPage"></span> of <span x-text="userTotalPages"></span>
                                </span>
                                <button @click="changePageUser(userCurrentPage + 1)" :disabled="userCurrentPage === userTotalPages"
                                    class="px-4 py-2 text-sm bg-gray-200 rounded disabled:opacity-50">
                                    Next &raquo;
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm">Show</label>
                                <select x-model="perPageUser" @change="updateUserPerPage(perPageUser)"
                                    class="px-6 py-2 text-sm rounded">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                                <span class="text-sm">entries</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>       
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('users', () => ({
        loading: false,
        users: [],
        userCurrentPage: 1,
        userTotalPages: 1,
        perPageUser: 5,
        searchUser: '',

        init() {
            this.getUsers();
        },

        async getUsers() {
            this.loading = true;
            try {
                const response = await axios.get('/getUsers', {
                    params: {
                        page: this.userCurrentPage,
                        perPage: this.perPageUser,
                        search: this.searchUser,
                    }
                });
                this.users = response.data.data;
                this.userCurrentPage = response.data.current_page;
                this.userTotalPages = response.data.last_page;
            } catch (error) {
                console.error('Error fetching users:', error);
            } finally {
                this.loading = false;
            }
        },

        changePageUser(page) {
            if (page < 1 || page > this.userTotalPages) return;
            this.userCurrentPage = page;
            this.getUsers();
        },

        updateUserPerPage(perPage) {
            this.perPageUser = perPage;
            this.userCurrentPage = 1;
            this.getUsers();
        },

        updateUserModal: false,
        selectedUser: [],
        editUser(user) {
            this.selectedUser = {
                id: user.id,
                name: user.name,
                is_active: user.is_active ? '1' : '0',
                role: user.roles.length ? user.roles[0].name.toLowerCase() : 'user'
            };
            this.updateUserModal = true;
        },

        deleteUserModal: false,
        userToDelete: null,
        confirmDeleteUser(user) {
            this.userToDelete = user;
            this.deleteUserModal = true;
        },
        deleteConfirmed() {
            if (!this.userToDelete) return;

            axios.delete(`/user/delete/${this.userToDelete.id}`)
                .then(response => {
                    alert('User deleted successfully!');
                    this.getUsers();
                })
                .catch(error => {
                    alert('Error Deletion of User');
                    console.error('Error deleting user:', error);
                })
                .finally(() => {
                    this.deleteUserModal = false;
                    this.userToDelete = null;
                });
        },
    }));
});
</script>



