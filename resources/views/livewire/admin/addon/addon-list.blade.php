<div class="space-y-6 md:px-0">
    <x-slot:title>
        {{ t('Addon Services') }}
    </x-slot:title>
    <x-breadcrumb :items="[['label' => t('dashboard'), 'route' => route('admin.dashboard')], ['label' => t('addon_services')]]" />

    <!-- Header Section -->
    <div class="flex flex-col justify-between space-y-4 md:space-y-0 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ t('addon_services') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ t('Manage addon services available for purchase') }}
            </p>
        </div>

        <div class="flex flex-col gap-4 xl:flex-row space-y-3 justify-between sm:space-y-0">
            <div class="flex justify-between items-center gap-4">
                <x-button.primary wire:navigate href="{{ route('admin.addons.create') }}">
                    <x-heroicon-m-plus class="w-4 h-4 mr-2" />
                    {{ t('Create New Addon') }}
                </x-button.primary>
            </div>
        </div>
    </div>

    <!-- Addons Cards View -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($addons as $addon)
            <div
                class="flex flex-col relative overflow-hidden bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow h-full">
                <!-- Featured/Badge -->
                @if($addon->badge)
                    <div class="absolute top-0 right-0">
                        <div class="px-3 py-1 text-xs font-medium text-white bg-primary-600 rounded-bl-lg">
                            {{ $addon->badge }}
                        </div>
                    </div>
                @endif

                <!-- Addon Header -->
                <div
                    class="p-4 border-b dark:border-gray-700 bg-gradient-to-br from-primary-50 to-purple-50 dark:from-gray-700 dark:to-gray-800">
                    <div class="flex items-center">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-purple-600">
                            <i class="{{ $addon->icon }} text-white text-xl"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $addon->name }}</h3>
                            <div class="flex mt-1 gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-200">
                                    {{ ucfirst($addon->type) }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $addon->is_active ? 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200' : 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-200' }}">
                                    {{ $addon->is_active ? t('active') : t('inactive') }}
                                </span>
                                @if($addon->is_featured)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200">
                                        {{ t('Featured') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Addon Details -->
                <div class="p-4 flex-grow">
                    <div class="flex justify-between items-center mb-3">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            PKR {{ number_format($addon->price) }}
                        </div>
                        @if($addon->category)
                            <div
                                class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                {{ $addon->category }}
                            </div>
                        @endif
                    </div>

                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                        {{ $addon->description ?: 'No description provided' }}
                    </p>

                    <!-- Credits Info -->
                    @if($addon->type === 'credits')
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 mb-2">
                                {{ t('What You Get') }}
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <x-heroicon-o-check-circle
                                        class="w-4 h-4 mt-0.5 text-success-500 dark:text-success-400 flex-shrink-0" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-medium">{{ number_format($addon->credit_amount) }}</span> Credits
                                    </span>
                                </li>
                                @if($addon->bonus_amount)
                                    <li class="flex items-start">
                                        <x-heroicon-o-gift
                                            class="w-4 h-4 mt-0.5 text-warning-500 dark:text-warning-400 flex-shrink-0" />
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">{{ number_format($addon->bonus_amount) }}</span> Bonus
                                        </span>
                                    </li>
                                @endif
                                <li class="flex items-start">
                                    <x-heroicon-o-shopping-bag
                                        class="w-4 h-4 mt-0.5 text-primary-500 dark:text-primary-400 flex-shrink-0" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-medium">{{ $addon->purchases->count() }}</span> Purchases
                                    </span>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Actions Footer -->
                <div class="mt-auto flex border-t divide-x dark:border-gray-700 dark:divide-gray-700">
                    <button wire:click="editAddon({{ $addon->id }})"
                        class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-gray-700 transition-colors">
                        <x-heroicon-o-pencil-square class="w-4 h-4 mr-1" />
                        {{ t('edit') }}
                    </button>

                    <button wire:click="toggleActive({{ $addon->id }})"
                        class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium {{ $addon->is_active ? 'text-warning-600 hover:bg-warning-50 dark:text-warning-400' : 'text-success-600 hover:bg-success-50 dark:text-success-400' }} dark:hover:bg-gray-700 transition-colors">
                        @if($addon->is_active)
                            <x-heroicon-o-pause-circle class="w-4 h-4 mr-1" />
                            {{ t('deactivate') }}
                        @else
                            <x-heroicon-o-play-circle class="w-4 h-4 mr-1" />
                            {{ t('activate') }}
                        @endif
                    </button>

                    <button wire:click="confirmDelete({{ $addon->id }})"
                        class="flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-danger-600 hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-gray-700 transition-colors">
                        <x-heroicon-o-trash class="w-4 h-4 mr-1" />
                        {{ t('delete') }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full p-6 text-center bg-white rounded-lg shadow dark:bg-gray-800">
                <x-heroicon-o-exclamation-circle class="w-12 h-12 mx-auto text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ t('No addons found') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ t('Get started by creating a new addon service') }}</p>
                <div class="mt-6">
                    <x-button.primary wire:navigate href="{{ route('admin.addons.create') }}">
                        <x-heroicon-m-plus class="-ml-1 mr-2 w-5 h-5" />
                        {{ t('Create Addon') }}
                    </x-button.primary>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($addons->hasPages())
        <div class="mt-4">
            {{ $addons->links() }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <x-modal.confirm-box :maxWidth="'lg'" :id="'delete-addon-modal'" title="{{ t('Delete Addon') }}"
        wire:model.defer="confirmingDeletion"
        description="{{ t('Are you sure you want to delete this addon? This action cannot be undone.') }}">
        <div
            class="border-neutral-200 border-neutral-500/30 flex justify-end items-center sm:block space-x-3 bg-gray-100 dark:bg-gray-700">
            <x-button.cancel-button wire:click="$set('confirmingDeletion', false)">
                {{ t('cancel') }}
            </x-button.cancel-button>
            <x-button.delete-button wire:click="delete" wire:loading.attr="disabled" class="mt-3 sm:mt-0">
                {{ t('delete') }}
            </x-button.delete-button>
        </div>
    </x-modal.confirm-box>
</div>