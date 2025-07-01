<div class="space-y-6 text-sm text-gray-800 dark:text-gray-200">
    <!-- Attributes Section -->
    <template x-if="log_properties?.attributes">
        <div class="border rounded-md p-4">
            <h3 class="font-semibold mb-2">New Attributes</h3>
            <div class="space-y-1">
                <template x-for="(value, key) in log_properties.attributes" :key="key">
                    <template x-if="key !== 'password'">
                        <div class="flex justify-between">
                            <span class="font-medium capitalize" x-text="key"></span>
                            <span x-text="value"></span>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </template>

    <!-- Old Values Section -->
    <template x-if="log_properties?.old">
        <div class="border rounded-md p-4">
            <h3 class="font-semibold mb-2">Old Values</h3>
            <div class="space-y-1">
                <template x-for="(value, key) in log_properties.old" :key="key">
                    <template x-if="key !== 'password'">
                        <div class="flex justify-between">
                            <span class="font-medium capitalize" x-text="key"></span>
                            <span x-text="value"></span>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </template>
</div>
