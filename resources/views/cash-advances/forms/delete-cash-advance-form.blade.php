<form method="POST" action="{{ route('cash_advance.delete') }}">
    @csrf
    <p class="mt-4 text-gray-600 dark:text-gray-300">
        Are you sure you want to delete this cash advance of <span class="text-red-500" x-text="toDeleteSelectedList.special_disbursing_officer"></span> on <span class="text-red-500" x-text="toDeleteSelectedList.cash_advance_date"></span> amounting to <span class="text-red-500" x-text="'₱' + parseFloat(toDeleteSelectedList.cash_advance_amount).toLocaleString()">?</span>
    </p>
    <!-- THIS ID MUST BE HIDDEN -->
    <div class="hidden">        
        <x-text-input id="id" name="id" class="mt-1 block w-full" x-bind:value="toDeleteSelectedList.id" />
    </div>
    <div class="mt-6 flex justify-end space-x-4">
        <button @submit.prevent @click="deleteCashAdvanceModal = false" type="button"
            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md">
            Cancel
        </button>
        <button type="submit"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md">
            Confirm
        </button>
    </div> 
</form>


