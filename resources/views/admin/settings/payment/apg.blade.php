<x-app-layout>
    <x-slot:title>
        APG Payment Settings
    </x-slot:title>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div>
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 sm:items-center justify-between">
                <div>
                    <h1 class="font-display text-3xl text-slate-900 dark:text-slate-200 font-medium">
                        Alfa Payment Gateway (APG)
                    </h1>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
                        Configure Bank Alfalah payment gateway integration
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
                <form method="POST" action="{{ route('admin.settings.payment.apg.update') }}" x-data="{
                    apgEnabled: {{ data_get($settings, 'apg_enabled') ? 'true' : 'false' }}
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
                                                <x-checkbox id="apg_enabled" name="apg_enabled"
                                                    :checked="$settings->apg_enabled" x-model="apgEnabled"
                                                    class="h-5 w-5 rounded border-gray-300 text-primary-600 transition duration-150 ease-in-out dark:border-gray-600 dark:bg-gray-700" />
                                                <x-label for="apg_enabled" value="Enable APG Payments"
                                                    class="ml-3 font-medium text-gray-900 dark:text-white" />
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Accept payments via Bank Alfalah
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-card>

                                <!-- Basic Configuration -->
                                <x-card>
                                    <x-slot:header>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            Merchant Configuration
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Enter your APG merchant credentials from Bank Alfalah
                                        </p>
                                    </x-slot:header>
                                    <x-slot:content>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Merchant ID -->
                                            <div>
                                                <x-label for="apg_merchant_id" value="Merchant ID"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_merchant_id" name="apg_merchant_id" type="text"
                                                    x-bind:disabled="!apgEnabled" class="mt-2 block w-full"
                                                    :value="$settings->apg_merchant_id" placeholder="233462" />
                                                <x-input-error for="apg_merchant_id" class="mt-2" />
                                            </div>

                                            <!-- Store ID -->
                                            <div>
                                                <x-label for="apg_store_id" value="Store ID"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_store_id" name="apg_store_id" type="text"
                                                    x-bind:disabled="!apgEnabled" class="mt-2 block w-full"
                                                    :value="$settings->apg_store_id" placeholder="524122" />
                                                <x-input-error for="apg_store_id" class="mt-2" />
                                            </div>

                                            <!-- Merchant Username -->
                                            <div>
                                                <x-label for="apg_merchant_username" value="Merchant Username"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_merchant_username" name="apg_merchant_username"
                                                    type="text" x-bind:disabled="!apgEnabled" class="mt-2 block w-full"
                                                    :value="$settings->apg_merchant_username" placeholder="yzowem" />
                                                <x-input-error for="apg_merchant_username" class="mt-2" />
                                            </div>

                                            <!-- Merchant Password -->
                                            <div>
                                                <x-label for="apg_merchant_password" value="Merchant Password"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_merchant_password" name="apg_merchant_password"
                                                    type="password" x-bind:disabled="!apgEnabled"
                                                    class="mt-2 block w-full"
                                                    :value="$settings->apg_merchant_password" />
                                                <x-input-error for="apg_merchant_password" class="mt-2" />
                                            </div>

                                            <!-- Merchant Hash -->
                                            <div class="md:col-span-2">
                                                <x-label for="apg_merchant_hash" value="Merchant Hash"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_merchant_hash" name="apg_merchant_hash" type="text"
                                                    x-bind:disabled="!apgEnabled"
                                                    class="mt-2 block w-full font-mono text-sm"
                                                    :value="$settings->apg_merchant_hash" />
                                                <x-input-error for="apg_merchant_hash" class="mt-2" />
                                            </div>

                                            <!-- Encryption Key 1 -->
                                            <div>
                                                <x-label for="apg_encryption_key1" value="Encryption Key 1 (16 chars)"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_encryption_key1" name="apg_encryption_key1" type="text"
                                                    x-bind:disabled="!apgEnabled" class="mt-2 block w-full font-mono"
                                                    :value="$settings->apg_encryption_key1" maxlength="16"
                                                    placeholder="dZUc9QUgPQP8pnKY" />
                                                <x-input-error for="apg_encryption_key1" class="mt-2" />
                                            </div>

                                            <!-- Encryption Key 2 -->
                                            <div>
                                                <x-label for="apg_encryption_key2" value="Encryption Key 2 (16 chars)"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <x-input id="apg_encryption_key2" name="apg_encryption_key2" type="text"
                                                    x-bind:disabled="!apgEnabled" class="mt-2 block w-full font-mono"
                                                    :value="$settings->apg_encryption_key2" maxlength="16"
                                                    placeholder="9956991048721627" />
                                                <x-input-error for="apg_encryption_key2" class="mt-2" />
                                            </div>

                                            <!-- Environment -->
                                            <div class="md:col-span-2">
                                                <x-label for="apg_environment" value="Environment"
                                                    class="text-base font-medium text-gray-900 dark:text-white" />
                                                <select id="apg_environment" name="apg_environment"
                                                    x-bind:disabled="!apgEnabled"
                                                    class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="sandbox" {{ data_get($settings, 'apg_environment') === 'sandbox' ? 'selected' : '' }}>Sandbox
                                                        (Testing)</option>
                                                    <option value="production" {{ data_get($settings, 'apg_environment') === 'production' ? 'selected' : '' }}>
                                                        Production (Live)</option>
                                                </select>
                                                <x-input-error for="apg_environment" class="mt-2" />
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-card>

                                <!-- Supported Features -->
                                <x-card>
                                    <x-slot:header>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            Supported Payment Methods
                                        </h3>
                                    </x-slot:header>
                                    <x-slot:content>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                            <div class="flex items-center space-x-3">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    Credit/Debit Cards</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    JazzCash</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    Bank Transfer</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    Alfa Wallet</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                                    Bank Account</span>
                                            </div>
                                        </div>

                                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                            <div class="flex items-start space-x-3">
                                                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5" />
                                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                                    <p class="font-medium">Currency: PKR (Pakistani Rupee)</p>
                                                    <p class="mt-1">APG supports payments in Pakistani Rupees only</p>
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot:content>
                                </x-card>

                                <!-- Action Buttons -->
                                <div
                                    class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
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