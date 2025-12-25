<div class="space-y-6 md:px-0">
    <x-slot:title>
        {{ $isUpdate ? t('Edit Addon') : t('Create Addon') }}
    </x-slot:title>

    <x-breadcrumb :items="[
        ['label' => t('dashboard'), 'route' => route('admin.dashboard')],
        ['label' => t('addon_services'), 'route' => route('admin.addons.index')],
        ['label' => $isUpdate ? t('Edit Addon') : t('Create Addon')]
    ]" />

    <form wire:submit.prevent="save">
        <div class="flex flex-col lg:flex-row gap-6 items-start" x-data="{ type: @entangle('type') }">
            <!-- Left Column -->
            <div class="w-full lg:w-6/12">
                <x-card class="rounded-lg shadow-sm mb-10">
                    <x-slot:header>
                        <div class="flex items-center gap-4">
                            <x-heroicon-o-document-text class="w-8 h-8 text-primary-600" />
                            <h1 class="text-xl font-semibold text-slate-700 dark:text-slate-300">
                                {{ t('Addon Details') }}
                            </h1>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="space-y-4">
                        <!-- Name -->
                        <div>
                            <div class="flex item-centar justify-start gap-1">
                                <span class="text-danger-500">*</span>
                                <x-label for="name" :value="t('Service Name')" />
                            </div>
                            <x-input id="name" type="text" class="block w-full mt-1" wire:model.live="name"
                                autocomplete="off" />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        <div>
                            <div class="flex items-center justify-between">
                                <div class="flex item-centar justify-start gap-1">
                                    <span class="text-danger-500">*</span>
                                    <x-label for="slug" :value="t('Slug')" />
                                </div>
                            </div>
                            <x-input id="slug" type="text" class="block w-full mt-1" wire:model="slug"
                                autocomplete="off" :disabled="$isUpdate" :title="$isUpdate ? t('Slug cannot be edited') : ''" />
                            <x-input-error for="slug" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <div class="flex item-centar justify-start gap-1">
                                <x-label for="description" :value="t('Description')" />
                            </div>
                            <x-textarea id="description" class="block w-full mt-1" rows="3" autocomplete="off"
                                wire:model="description"></x-textarea>
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        <!-- Type & Category -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="flex item-centar justify-start gap-1">
                                    <span class="text-danger-500">*</span>
                                    <x-label for="type" :value="t('Type')" />
                                </div>
                                <x-select id="type" class="block w-full mt-1" wire:model.live="type">
                                    <option value="credits">{{ t('Credits') }}</option>
                                    <option value="feature">{{ t('Feature Access') }}</option>
                                    <option value="one_time">{{ t('One-Time Service') }}</option>
                                </x-select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <div>
                                <div class="flex item-centar justify-start gap-1">
                                    <x-label for="category" :value="t('Category')" />
                                </div>
                                <x-select id="category" class="block w-full mt-1" wire:model="category">
                                    <option value="AI">{{ t('AI Services') }}</option>
                                    <option value="SMS">{{ t('SMS Credits') }}</option>
                                    <option value="Support">{{ t('Premium Support') }}</option>
                                    <option value="Features">{{ t('Features') }}</option>
                                    <option value="Other">{{ t('Other') }}</option>
                                </x-select>
                                <x-input-error for="category" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>

                <!-- Credits Configuration (Only for credits type) -->
                <x-card class="rounded-lg shadow-sm mb-6" x-show="type === 'credits'" x-cloak>
                    <x-slot:header>
                        <div class="flex items-center gap-4">
                            <x-heroicon-o-gift class="w-8 h-8 text-primary-600" />
                            <h1 class="text-xl font-semibold text-slate-700 dark:text-slate-300">
                                {{ t('Credits Configuration') }}
                            </h1>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="credit_amount" :value="t('Credit Amount')" />
                                <x-input id="credit_amount" type="number" step="0.01" class="block w-full mt-1"
                                    wire:model="credit_amount" autocomplete="off" />
                                <x-input-error for="credit_amount" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="bonus_amount" :value="t('Bonus Credits')" />
                                <x-input id="bonus_amount" type="number" step="0.01" class="block w-full mt-1"
                                    wire:model="bonus_amount" autocomplete="off" />
                                <x-input-error for="bonus_amount" class="mt-2" />
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>

            <!-- Right Column -->
            <div class="w-full lg:w-6/12">
                <x-card class="rounded-lg shadow-sm mb-6">
                    <x-slot:header>
                        <div class="flex items-center gap-4">
                            <x-heroicon-o-currency-dollar class="w-8 h-8 text-primary-600" />
                            <h1 class="text-xl font-semibold text-slate-700 dark:text-slate-300">
                                {{ t('Pricing & Display') }}
                            </h1>
                        </div>
                    </x-slot:header>
                    <x-slot:content class="space-y-4">
                        <!-- Price -->
                        <div>
                            <div class="flex item-center justify-start gap-1">
                                <span class="text-danger-500">*</span>
                                <x-label for="price" :value="t('Price (PKR)')" />
                            </div>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">PKR</span>
                                </div>
                                <x-input id="price" type="number" step="0.01" min="0" autocomplete="off"
                                    class="block w-full pl-12" wire:model="price" />
                            </div>
                            <x-input-error for="price" class="mt-2" />
                        </div>

                        <!-- Icon -->
                        <div>
                            <x-label for="icon" :value="t('Icon (Font Awesome)')" />
                            <x-input id="icon" type="text" class="block w-full mt-1" wire:model="icon"
                                placeholder="fas fa-coins" autocomplete="off" />
                            <p class="text-xs text-gray-500 mt-1">e.g., fas fa-coins, fas fa-star, fas fa-gift</p>
                            <x-input-error for="icon" class="mt-2" />
                        </div>

                        <!-- Badge -->
                        <div>
                            <x-label for="badge" :value="t('Badge (Optional)')" />
                            <x-input id="badge" type="text" class="block w-full mt-1" wire:model="badge"
                                placeholder="Popular, Best Value" autocomplete="off" />
                            <x-input-error for="badge" class="mt-2" />
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <x-label for="sort_order" :value="t('Sort Order')" />
                            <x-input id="sort_order" type="number" class="block w-full mt-1" wire:model="sort_order"
                                autocomplete="off" />
                            <x-input-error for="sort_order" class="mt-2" />
                        </div>

                        <!-- Flags -->
                        <div class="space-y-4 rounded-md bg-white dark:bg-transparent">
                            <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>
                            <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
                                <div>
                                    <div class="flex justify-start items-center gap-3">
                                        <x-toggle id="is_active" name="is_active" :value="!!$is_active"
                                            wire:model="is_active" />
                                        <x-label for="is_active" class="font-medium">
                                            {{ t('Active') }}
                                        </x-label>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ t('Make this addon available for purchase') }}
                                    </p>
                                </div>

                                <div>
                                    <div class="flex justify-start items-center gap-3">
                                        <x-toggle id="is_featured" name="is_featured" :value="!!$is_featured"
                                            wire:model="is_featured" />
                                        <x-label for="is_featured" class="font-medium">
                                            {{ t('Featured') }}
                                        </x-label>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ t('Highlight this addon in the marketplace') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-card>
            </div>
        </div>

        <!-- Footer Actions Bar -->
        <div
            class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 z-10">
            <div class="flex justify-end px-6 py-3">
                <x-button.secondary class="mx-2" onclick="window.history.back()">
                    {{ t('cancel') }}
                </x-button.secondary>
                <x-button.loading-button type="submit">
                    {{ $isUpdate ? t('Update Addon') : t('Create Addon') }}
                </x-button.loading-button>
            </div>
        </div>
    </form>
</div>