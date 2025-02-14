    <!-- error and success messages -->
    @if ($errors->any())
        <div class="p-4 my-5 text-sm text-red-800 bg-red-100 rounded-lg" role="alert">
            <strong class="block font-medium mb-2">There were some issues with your submission:</strong>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('message'))
        <div x-data="{ show: true }" x-show="show" 
            class="p-4 my-5 text-sm text-green-800 bg-green-100 rounded-lg" 
            x-init="setTimeout(() => show = false, 3000)" 
            role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('message') }}</span>   
            </div>
        </div>
    @endif

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" 
            class="p-4 my-5 text-sm text-green-800 bg-green-100 rounded-lg" 
            x-init="setTimeout(() => show = false, 3000)" 
            role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" 
            class="p-4 my-5 text-sm text-red-800 bg-red-100 rounded-lg" 
            role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" 
            class="p-4 my-5 text-sm text-blue-800 bg-blue-100 rounded-lg" 
            x-init="setTimeout(() => show = false, 3000)" 
            role="alert">
            <div class="flex justify-between items-center">
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif
