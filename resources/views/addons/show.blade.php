<x-app-layout>
    <div class="space-y-6">
        <x-slot:title>
            {{ $addon->name }}
        </x-slot:title>

        <x-breadcrumb :items="[
            ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
            ['label' => t('addon_services'), 'route' => tenant_route('tenant.addons.index')],
            ['label' => $addon->name]
        ]" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Addon Details Card -->
                <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-primary-100 to-purple-100 dark:from-primary-900 dark:to-purple-900">
                                <i class="{{ $addon->icon }} text-3xl text-primary-600 dark:text-primary-400"></i>
                            </div>
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $addon->name }}</h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $addon->description }}</p>
                                
                                <div class="flex flex-wrap gap-2 mt-4">
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-200">
                                        {{ ucfirst($addon->type) }}
                                    </span>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $addon->category }}
                                    </span>
                                    @if($addon->badge)
                                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                                            {{ $addon->badge }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- What You Get -->
                @if($addon->type === 'credits')
                    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('What You Get') }}</h2>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-4 rounded-lg bg-success-50 dark:bg-success-900/20">
                                    <x-heroicon-o-currency-dollar class="w-6 h-6 text-success-600 dark:text-success-400" />
                                    <div>
                                        <p class="font-medium text-success-900 dark:text-success-100">{{ number_format($addon->credit_amount) }} {{ t('Credits') }}</p>
                                        <p class="text-sm text-success-700 dark:text-success-300">{{ t('Base credits') }}</p>
                                    </div>
                                </div>
                                @if($addon->bonus_amount)
                                    <div class="flex items-center gap-3 p-4 rounded-lg bg-warning-50 dark:bg-warning-900/20">
                                        <x-heroicon-o-gift class="w-6 h-6 text-warning-600 dark:text-warning-400" />
                                        <div>
                                            <p class="font-medium text-warning-900 dark:text-warning-100">{{ number_format($addon->bonus_amount) }} {{ t('Bonus Credits') }}</p>
                                            <p class="text-sm text-warning-700 dark:text-warning-300">{{ t('Extra bonus credits') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex items-center gap-3 p-4 rounded-lg bg-primary-50 dark:bg-primary-900/20 border-2 border-primary-200 dark:border-primary-800">
                                    <x-heroicon-o-check-circle class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                    <div>
                                        <p class="font-bold text-primary-900 dark:text-primary-100">{{ number_format($addon->credit_amount + $addon->bonus_amount) }} {{ t('Total Credits') }}</p>
                                        <p class="text-sm text-primary-700 dark:text-primary-300">{{ t('Total you will receive') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Purchase History -->
                @if(count($userPurchases) > 0)
                    <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ t('Your Purchase History') }}</h2>
                            <div class="space-y-3">
                                @foreach($userPurchases as $purchase)
                                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">PKR {{ number_format($purchase->amount_paid) }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $purchase->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $purchase->status === 'completed' ? 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200' : 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200' }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Price Card -->
                <div class="bg-white rounded-lg shadow dark:bg-gray-800 sticky top-6">
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ t('Price') }}</p>
                            <p class="text-4xl font-bold text-primary-600 dark:text-primary-400">PKR {{ number_format($addon->price) }}</p>
                        </div>

                        <form action="{{ tenant_route('tenant.addons.purchase', ['addon' => $addon->slug]) }}" method="POST" class="space-y-4">
                            @csrf
                            <x-button.primary type="submit" class="w-full justify-center">
                                <x-heroicon-m-shopping-cart class="w-5 h-5 mr-2" />
                                {{ t('Purchase Now') }}
                            </x-button.primary>
                        </form>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-success-500" />
                                <span>{{ t('Instant activation') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <x-heroicon-o-shield-check class="w-5 h-5 text-success-500" />
                                <span>{{ t('Secure payment') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <x-heroicon-o-clock class="w-5 h-5 text-success-500" />
                                <span>{{ t('24/7 support') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>