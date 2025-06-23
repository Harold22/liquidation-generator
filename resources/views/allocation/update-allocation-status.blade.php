<form method="POST" action="{{ route('allocation.updateStatus') }}">
    @csrf

    <!-- Hidden ID -->
    <input type="hidden" name="id" :value="selectedAllocation">

    <!-- Hidden Status (set to 'liquidated') -->
    <input type="hidden" name="status" value="liquidated">

    <div class="flex justify-end gap-4">
        <button type="button" @click="updateStatusModal = false" class="px-4 py-2 text-sm bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            Cancel
        </button>
        <x-primary-button type="submit" >
            Confirm
        </x-primary-button>

    </div>
</form>


