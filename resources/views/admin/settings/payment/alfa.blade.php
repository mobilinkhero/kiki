<x-app-layout>
    <x-slot:title>
        {{ t('alfa_payment_settings') ?? 'Alfa Payment Settings' }}
    </x-slot:title>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div>
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 sm:items-center justify-between">
                <div>
                    <h1 class="font-display text-3xl text-slate-900 dark:text-slate-200 font-medium">
                        {{ t('alfa_payment_settings') ?? 'Alfa Payment Settings' }}
                    </h1>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        {{ t('configure_alfa_payments_description') ?? 'Configure Bank Alfalah payment gateway for secure transactions' }}
                    </p>
                </div>
                <x-button.secondary type="button" onclick="history.back()">
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                    {{ t('back') }}
                </x-button.secondary>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900 mt-6">
            <div class="max-w-7xl mx-auto">
                <form id="alfa-settings-form" method="POST"
                    action="{{ route('admin.settings.payment.alfa.update') }}" x-data="{
                        alfaEnabled: {{ $settings->alfa_enabled ? 'true' : 'false' }}
                    }">
                    @csrf
                    <x-card>
                        <x-slot:content>
                            <div class="space-y-8">
                                <!-- Enable/Disable Section -->
                                <x-card>
                                    <x-slot:content>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <x-checkbox id="alfa_enabled" name="alfa_enabled"
                                                    :checked="$settings->alfa_enabled" x-model="alfaEnabled"
                                                    class="h-5 w-5 rounded border-gray-300 text-primary-600 transition duration-150 ease-in-out dark:border-gray-600 dark:bg-gray-700" />
                                                <x-label for="alfa_enabled" value="{{ t('enable_alfa_payments') ?? 'Enable Alfa Payments' }}"
                                                    class="ml-3 font-medium text-gray-900 dark:text-white" />
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ t('enable_alfa_payments_description') ?? 'Accept payments via Bank Alfalah' }}
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-card>

                                <!-- Basic Settings Card -->
                                <x-card>
                                    <x-slot:header>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ t('alfa_configuration') ?? 'Alfa Configuration' }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ t('alfa_configuration_description') ?? 'Enter your Bank Alfalah merchant credentials' }}
                                        </p>
                                    </x-slot:header>
                                    <x-slot:content>
                                        <div class="space-y-6">
                                            <!-- Mode Selection -->
                                            <div>
                                                <x-label for="alfa_mode" :value="t('environment') ?? 'Environment'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <select id="alfa_mode" name="alfa_mode" x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="sandbox" {{ $settings->alfa_mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                                    <option value="production" {{ $settings->alfa_mode === 'production' ? 'selected' : '' }}>Production (Live)</option>
                                                </select>
                                            </div>

                                            <!-- Merchant ID -->
                                            <div>
                                                <x-label for="alfa_merchant_id" :value="t('merchant_id') ?? 'Merchant ID'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="alfa_merchant_id" name="alfa_merchant_id" type="text"
                                                    x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                    :value="$settings->alfa_merchant_id"
                                                    placeholder="Enter Merchant ID" />
                                                <x-input-error for="alfa_merchant_id" class="mt-2" />
                                            </div>

                                            <!-- Store ID -->
                                            <div>
                                                <x-label for="alfa_store_id" :value="t('store_id') ?? 'Store ID'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="alfa_store_id" name="alfa_store_id" type="text"
                                                    x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                    :value="$settings->alfa_store_id"
                                                    placeholder="Enter Store ID" />
                                                <x-input-error for="alfa_store_id" class="mt-2" />
                                            </div>

                                            <!-- Merchant Hash -->
                                            <div>
                                                <x-label for="alfa_merchant_hash" :value="t('merchant_hash') ?? 'Merchant Hash'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="alfa_merchant_hash" name="alfa_merchant_hash" type="password"
                                                    x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                    :value="$settings->alfa_merchant_hash"
                                                    placeholder="Enter Merchant Hash" />
                                                <x-input-error for="alfa_merchant_hash" class="mt-2" />
                                            </div>

                                            <!-- Merchant Username -->
                                            <div>
                                                <x-label for="alfa_merchant_username" :value="t('merchant_username') ?? 'Merchant Username'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="alfa_merchant_username" name="alfa_merchant_username" type="text"
                                                    x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                    :value="$settings->alfa_merchant_username"
                                                    placeholder="Enter Merchant Username" />
                                                <x-input-error for="alfa_merchant_username" class="mt-2" />
                                            </div>

                                            <!-- Merchant Password -->
                                            <div>
                                                <x-label for="alfa_merchant_password" :value="t('merchant_password') ?? 'Merchant Password'"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="alfa_merchant_password" name="alfa_merchant_password" type="password"
                                                    x-bind:disabled="!alfaEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                                    :value="$settings->alfa_merchant_password"
                                                    placeholder="Enter Merchant Password" />
                                                <x-input-error for="alfa_merchant_password" class="mt-2" />
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-card>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <x-button.secondary type="button" onclick="history.back()">
                                        {{ t('cancel') }}
                                    </x-button.secondary>
                                    <x-button.primary type="submit">
                                        <x-heroicon-o-check class="w-4 h-4 mr-2" />
                                        {{ t('save_settings') }}
                                    </x-button.primary>
                                </div>
                            </div>
                        </x-slot:content>
                    </x-card>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
