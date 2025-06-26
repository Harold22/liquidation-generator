<!-- Toast Notification Container -->
<div class="fixed top-5 right-5 z-50 space-y-4 w-full max-w-sm">
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-4 text-sm text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-lg relative"
            role="alert"
        >
            <div class="flex items-start justify-between">
                <div>
                    <strong class="block font-medium mb-1">There were some issues with your submission:</strong>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="ml-4 text-red-600 hover:text-red-900 font-bold text-lg">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- Success --}}
    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-4 text-sm text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-lg relative"
            role="alert"
        >
            <div class="flex items-start justify-between">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-green-600 hover:text-green-900 font-bold text-lg">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- Error --}}
    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-4 text-sm text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-lg relative"
            role="alert"
        >
            <div class="flex items-start justify-between">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-4 text-red-600 hover:text-red-900 font-bold text-lg">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- Info --}}
    @if (session('info'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-4 text-sm text-blue-800 bg-blue-100 border border-blue-300 rounded-lg shadow-lg relative"
            role="alert"
        >
            <div class="flex items-start justify-between">
                <span>{{ session('info') }}</span>
                <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-900 font-bold text-lg">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- Generic Message --}}
    @if (session('message'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-4 text-sm text-gray-800 bg-gray-100 border border-gray-300 rounded-lg shadow-lg relative"
            role="alert"
        >
            <div class="flex items-start justify-between">
                <span>{{ session('message') }}</span>
                <button @click="show = false" class="ml-4 text-gray-600 hover:text-gray-900 font-bold text-lg">
                    &times;
                </button>
            </div>
        </div>
    @endif

</div>
