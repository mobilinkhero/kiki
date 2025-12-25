<div class="space-y-6">
    <x-slot:title>
        {{ t('Addon Services') }}
    </x-slot:title>

    <x-breadcrumb :items="[
        ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
        ['label' => t('addon_services')]
    ]" />

    <!-- Header with Credit Balance -->
    <div class="flex flex-col justify-between space-y-4 md:space-y-0 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ t('addon_services') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ t('Enhance your experience with premium addons') }}
            </p>
        </div>

        @if($userCredits !== null)
            <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-purple-600 rounded-lg shadow-lg">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-currency-dollar class="w-8 h-8 text-white" />
                    <div class="text-white">
                        <p class="text-xs opacity-90">{{ t('Your Balance') }}</p>
                        <p class="text-2xl font-bold">{{ number_format($userCredits, 2) }} <span
                                class="text-sm font-normal">{{ t('credits') }}</span></p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Addons Grid -->
    @if($addons->isEmpty())
        <div class="p-6 text-center bg-white rounded-lg shadow dark:bg-gray-800">
            <x-heroicon-o-exclamation-circle class="w-12 h-12 mx-auto text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ t('No addons found') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ t('No addon services available at the moment') }}
            </p>
        </div>
    @else
        @foreach($addons as $category => $categoryAddons)
            <div class="space-y-4">
                <h2 class="flex items-center text-xl font-semibold text-gray-900 dark:text-white">
                    <x-heroicon-o-tag class="w-5 h-5 mr-2 text-primary-500" />
                    {{ $category }}
                </h2>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($categoryAddons as $addon)
                        <div
                            class="relative flex flex-col overflow-hidden bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow">
                            <!-- Badge -->
                            @if($addon->badge)
                                <div class="absolute top-0 right-0 z-10">
                                    <div class="px-3 py-1 text-xs font-medium text-white bg-primary-600 rounded-bl-lg">
                                        {{ $addon->badge }}
                                    </div>
                                </div>
                            @endif

                            <!-- Card Content -->
                            <div class="flex flex-col flex-grow p-6">
                                <!-- Icon -->
                                <div
                                    class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-primary-100 to-purple-100 dark:from-primary-900 dark:to-purple-900">
                                    <i class="{{ $addon->icon }} text-2xl text-primary-600 dark:text-primary-400"></i>
                                </div>

                                <!-- Name -->
                                <h3 class="mb-2 text-lg font-semibold text-center text-gray-900 dark:text-white">
                                    {{ $addon->name }}
                                </h3>

                                <!-- Description -->
                                <p class="flex-grow mb-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                    {{ $addon->description ?: 'Premium addon service' }}
                                </p>

                                <!-- Credits Info -->
                                @if($addon->type === 'credits')
                                    <div class="mb-4 space-y-2">
                                        <div
                                            class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-success-50 dark:bg-success-900/20">
                                            <x-heroicon-o-currency-dollar class="w-4 h-4 text-success-600 dark:text-success-400" />
                                            <span class="text-sm font-medium text-success-700 dark:text-success-300">
                                                {{ number_format($addon->credit_amount) }} {{ t('Credits') }}
                                            </span>
                                        </div>
                                        @if($addon->bonus_amount)
                                            <div
                                                class="flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-warning-50 dark:bg-warning-900/20">
                                                <x-heroicon-o-gift class="w-4 h-4 text-warning-600 dark:text-warning-400" />
                                                <span class="text-sm font-medium text-warning-700 dark:text-warning-300">
                                                    +{{ number_format($addon->bonus_amount) }} {{ t('Bonus') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Price -->
                                <div class="mb-4 text-center">
                                    <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                        PKR {{ number_format($addon->price) }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="mt-auto space-y-2">
                                    <form action="{{ tenant_route('tenant.addons.purchase', ['addon' => $addon->slug]) }}"
                                        method="POST">
                                        @csrf
                                        <x-button.primary type="submit" class="w-full">
                                            <x-heroicon-m-shopping-cart class="w-4 h-4 mr-2" />
                                            {{ t('Purchase Now') }}
                                        </x-button.primary>
                                    </form>

                                    <a href="{{ tenant_route('tenant.addons.show', ['addon' => $addon->slug]) }}"
                                        class="block text-sm text-center text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                        {{ t('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>