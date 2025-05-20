<div class="space-y-4">
    <div class="space-y-4 text-sm">
        <template x-if="log_properties?.attributes">
            <div>
                <h3 class="font-medium text-green-700 mb-1">Attributes:</h3>
                <template x-for="(value, key) in log_properties.attributes" :key="key">
                    <template x-if="key !== 'password'">
                        <div>
                            <strong x-text="key"></strong>:
                            <span class="text-green-600" x-text="value"></span>
                        </div>
                    </template>
                </template>
            </div>
        </template>

        <!-- Old Values -->
        <template x-if="log_properties?.old">
            <div>
                <h3 class="font-medium text-red-700 mb-1">Old Values:</h3>
                <template x-for="(value, key) in log_properties.old" :key="key">
                    <template x-if="key !== 'password'">
                        <div>
                            <strong x-text="key"></strong>:
                            <span class="text-red-600" x-text="value"></span>
                        </div>
                    </template>
                </template>
            </div>
        </template>
    </div>
</div>