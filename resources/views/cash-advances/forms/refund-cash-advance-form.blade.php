<form method="POST" action="{{ route('refund.add') }}" >
    <div class="mt-4 space-y-4">
        @csrf
        <div class="hidden">        
            <x-text-input id="cash_advance_id" name="cash_advance_id" class="mt-1 block w-full" x-bind:value="refundList.id" />
        </div>

        <!-- Special Disbursing Officer -->
        <div>
            <x-input-label for="special_disbursing_officer" class="text-sm">
                {{ __('Special Disbursing Officer') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input readonly id="special_disbursing_officer" name="special_disbursing_officer" type="text" class="mt-1 block w-full text-sm" x-bind:value="refundList.special_disbursing_officer" />
        </div>
        <!-- Cash Advance Amount and Date -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="cash_advance_amount" class="text-sm">
                    {{ __('Cash Advance Amount') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input readonly id="cash_advance_amount" name="cash_advance_amount" type="number" class="mt-1 block w-full text-sm" x-bind:value="refundList.cash_advance_amount"/>
            </div>

            <div>
                <x-input-label for="cash_advance_date" class="text-sm">
                    {{ __('Cash Advance Date') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input readonly id="cash_advance_date" name="cash_advance_date" type="date" class="mt-1 block w-full text-sm" x-bind:value="refundList.cash_advance_date"/>
            </div>
        </div>
        <!-- refund -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="hidden">        
                <x-text-input id="refund_id" name="refund_id" class="mt-1 block w-full" x-bind:value="refund_id" />
            </div>
            <div>
                <x-input-label for="amount_refunded" class="text-sm">
                    {{ __('Amount Refunded') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input 
                    id="amount_refunded" 
                    name="amount_refunded" 
                    type="number" 
                    class="mt-1 block w-full text-sm" 
                    x-model="amount_refunded"
                    x-bind:required="amount_refunded || date_refunded || official_receipt ? true : false" />
            </div>

            <div>
                <x-input-label for="date_refunded" class="text-sm">
                    {{ __('Date Refunded') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input 
                    id="date_refunded" 
                    name="date_refunded" 
                    type="date" 
                    class="mt-1 block w-full text-sm" 
                    x-model="date_refunded"
                    x-bind:required="amount_refunded || date_refunded || official_receipt ? true : false" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="official_receipt" class="text-sm">
                    {{ __('Official Receipt') }} <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input 
                    id="official_receipt" 
                    name="official_receipt" 
                    type="text" 
                    class="mt-1 block w-full text-sm uppercase" 
                    x-model="official_receipt"
                    x-bind:required="amount_refunded || date_refunded || official_receipt ? true : false" />
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex item-center justify-between">
            <div class="flex items-center gap-4">
                <x-primary-button class="text-sm px-4 py-2">{{ __('Save') }}</x-primary-button>
            </div>
            <div show="refund_id" class="flex items-center gap-4">
                <x-delete-button type="button" @click="deleteRefund()" class="text-sm px-4 py-2 bg-red-600">{{ __('Delete') }}</x-delete-button>
            </div>
        </div>
    </div>
</form>
