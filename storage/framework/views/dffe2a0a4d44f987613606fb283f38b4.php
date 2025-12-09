<div class="mx-auto px-4 md:px-0">
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(t('api_integration_and_access')); ?>

     <?php $__env->endSlot(); ?>

    <!-- Page Heading -->
    <div class="pb-6">
        <?php if (isset($component)) { $__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-heading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(t('system_setting')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff)): ?>
<?php $attributes = $__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff; ?>
<?php unset($__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff)): ?>
<?php $component = $__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff; ?>
<?php unset($__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff); ?>
<?php endif; ?>
    </div>

    <div class="flex flex-wrap lg:flex-nowrap gap-4">
        <!-- Sidebar Menu -->
        <div class="w-full lg:w-1/5">
            <?php if (isset($component)) { $__componentOriginal427362ae11d707f153ccf2a9f38ce42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal427362ae11d707f153ccf2a9f38ce42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tenant-system-settings-navigation','data' => ['wire:ignore' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tenant-system-settings-navigation'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:ignore' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal427362ae11d707f153ccf2a9f38ce42a)): ?>
<?php $attributes = $__attributesOriginal427362ae11d707f153ccf2a9f38ce42a; ?>
<?php unset($__attributesOriginal427362ae11d707f153ccf2a9f38ce42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal427362ae11d707f153ccf2a9f38ce42a)): ?>
<?php $component = $__componentOriginal427362ae11d707f153ccf2a9f38ce42a; ?>
<?php unset($__componentOriginal427362ae11d707f153ccf2a9f38ce42a); ?>
<?php endif; ?>
        </div>

        <!-- Main Content -->
        <div class="flex-1 space-y-5">
            <form wire:submit="save" class="space-y-6">
                <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['class' => 'rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'rounded-lg']); ?>
                     <?php $__env->slot('header', null, []); ?> 
                        <?php if (isset($component)) { $__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-heading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php echo e(t('api_integration_and_access')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff)): ?>
<?php $attributes = $__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff; ?>
<?php unset($__attributesOriginal32b3aedb79dcb21d2517daf1cd4b81ff); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff)): ?>
<?php $component = $__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff; ?>
<?php unset($__componentOriginal32b3aedb79dcb21d2517daf1cd4b81ff); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginald4840e1146262bfa3abec1048daf8661 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4840e1146262bfa3abec1048daf8661 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                            <?php echo e(t('api_integration_and_access_description')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4840e1146262bfa3abec1048daf8661)): ?>
<?php $attributes = $__attributesOriginald4840e1146262bfa3abec1048daf8661; ?>
<?php unset($__attributesOriginald4840e1146262bfa3abec1048daf8661); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4840e1146262bfa3abec1048daf8661)): ?>
<?php $component = $__componentOriginald4840e1146262bfa3abec1048daf8661; ?>
<?php unset($__componentOriginald4840e1146262bfa3abec1048daf8661); ?>
<?php endif; ?>
                     <?php $__env->endSlot(); ?>

                     <?php $__env->slot('content', null, []); ?> 
                        <div class="space-y-6">
                            <!-- Enable API Access -->
                            <div>
                                <h3 class="text-base font-medium text-secondary-900 dark:text-white">
                                    <?php echo e(t('enable_api_access')); ?></h3>

                                <div class="mt-2">
                                    <?php if (isset($component)) { $__componentOriginal592735d30e1926fbb04ff9e089d1fccf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal592735d30e1926fbb04ff9e089d1fccf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toggle','data' => ['value' => $isEnabled,'@toggleChanged.window' => '$wire.toggleApiAccess($event.detail.value)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isEnabled),'@toggle-changed.window' => '$wire.toggleApiAccess($event.detail.value)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal592735d30e1926fbb04ff9e089d1fccf)): ?>
<?php $attributes = $__attributesOriginal592735d30e1926fbb04ff9e089d1fccf; ?>
<?php unset($__attributesOriginal592735d30e1926fbb04ff9e089d1fccf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal592735d30e1926fbb04ff9e089d1fccf)): ?>
<?php $component = $__componentOriginal592735d30e1926fbb04ff9e089d1fccf; ?>
<?php unset($__componentOriginal592735d30e1926fbb04ff9e089d1fccf); ?>
<?php endif; ?>
                                </div>
                            </div>

                            <!-- API Token -->
                            <div
                                class="bg-white dark:bg-secondary-800 rounded-lg border border-secondary-200 dark:border-secondary-700 p-5">
                                <h3 class="text-lg font-semibold text-secondary-900 dark:text-white">
                                    <?php echo e(t('api_token')); ?></h3>
                                <div class="mt-4 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2" x-data="{
                                        copied: false,
                                        copyText() {
                                            const text = $refs.currentToken?.value;
                                            if (!text) {
                                                showNotification('No text found to copy', 'danger');
                                                return;
                                            }
                                            copyToClipboard(text);
                                            this.copied = true;
                                            setTimeout(() => this.copied = false, 2000);
                                        }
                                    }">

                                    <!-- Input Field -->
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'token','type' => 'text','class' => 'flex-1 w-full','value' => $currentToken,'readonly' => true,'xRef' => 'currentToken']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'token','type' => 'text','class' => 'flex-1 w-full','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentToken),'readonly' => true,'x-ref' => 'currentToken']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>

                                    <!-- Buttons -->
                                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                        <?php if (isset($component)) { $__componentOriginal36263f9a6b42b4504b8be98f2116ea00 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.secondary','data' => ['type' => 'button','wire:click' => 'generateNewToken','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'generateNewToken','class' => 'w-full sm:w-auto']); ?>
                                            <?php echo e(t('generate_new_token')); ?>

                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $attributes = $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $component = $__componentOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentToken): ?>
                                        <?php if (isset($component)) { $__componentOriginal36263f9a6b42b4504b8be98f2116ea00 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.secondary','data' => ['xOn:click' => 'copyText()','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'copyText()','class' => 'w-full sm:w-auto']); ?>
                                            <span x-text="copied ? 'Copied' : 'Copy'"><?php echo e(t('copy')); ?></span>
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $attributes = $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $component = $__componentOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newTokenGenerated): ?>
                                <p class="mt-2 text-sm text-warning-600 dark:text-warning-500">
                                    <?php echo e(t('please_copy_your_new_api_token_now')); ?>

                                </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- API Endpoint Information -->
                            <div
                                class="bg-white dark:bg-secondary-800 rounded-lg border border-secondary-200 dark:border-secondary-700 p-5 mt-6 space-y-6">
                                <h3 class="text-lg font-semibold text-secondary-900 dark:text-white">
                                    <?php echo e(t('api_endpoint_information')); ?></h3>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-2">
                                    <!-- API Base URL -->
                                    <div class="">
                                        <h4
                                            class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3">
                                            <?php echo e(t('api_base_url')); ?>

                                        </h4>
                                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2"
                                            x-data="{
                                                copiedApiUrl: false,
                                                copyApiUrl() {
                                                    const text = $refs.apiUrl?.value;
                                                    if (!text) {
                                                        showNotification('No text found to copy', 'danger');
                                                        return;
                                                    }
                                                    copyToClipboard(text);
                                                    this.copiedApiUrl = true;
                                                    setTimeout(() => this.copiedApiUrl = false, 2000);
                                                }
                                            }">
                                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'api_url','type' => 'text','class' => 'flex-1 w-full','value' => config('app.url') . '/api/v1/','readonly' => true,'xRef' => 'apiUrl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'api_url','type' => 'text','class' => 'flex-1 w-full','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('app.url') . '/api/v1/'),'readonly' => true,'x-ref' => 'apiUrl']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal36263f9a6b42b4504b8be98f2116ea00 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.secondary','data' => ['xOn:click' => 'copyApiUrl()','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'copyApiUrl()','class' => 'w-full sm:w-auto']); ?>
                                                <span x-text="copiedApiUrl ? 'Copied' : 'Copy'"><?php echo e(t('copy')); ?></span>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $attributes = $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $component = $__componentOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Tenant Subdomain -->
                                    <div>
                                        <h4 class="text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                                            <?php echo e(t('tenant_subdomain')); ?>

                                        </h4>
                                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2"
                                            x-data="{
                                                copiedSubdomain: false,
                                                copySubdomain() {
                                                    const text = $refs.tenantSubdomain?.value;
                                                    if (!text) {
                                                        showNotification('No text found to copy', 'danger');
                                                        return;
                                                    }
                                                    copyToClipboard(text);
                                                    this.copiedSubdomain = true;
                                                    setTimeout(() => this.copiedSubdomain = false, 2000);
                                                }
                                            }">
                                            <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'tenant_subdomain','type' => 'text','class' => 'flex-1 w-full','value' => $subdomain,'readonly' => true,'xRef' => 'tenantSubdomain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'tenant_subdomain','type' => 'text','class' => 'flex-1 w-full','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subdomain),'readonly' => true,'x-ref' => 'tenantSubdomain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                            <?php if (isset($component)) { $__componentOriginal36263f9a6b42b4504b8be98f2116ea00 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.secondary','data' => ['xOn:click' => 'copySubdomain()','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'copySubdomain()','class' => 'w-full sm:w-auto']); ?>
                                                <span x-text="copiedSubdomain ? 'Copied' : 'Copy'"><?php echo e(t('copy')); ?></span>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $attributes = $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $component = $__componentOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
                                        </div>
                                    </div>
                                </div>


                                <!-- Example API Endpoint Section - UPDATED EVENT LISTENER -->
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">
                                        <?php echo e(t('example_api_endpoint')); ?></h4>
                                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2" x-data="{
                                            copiedExample: false,
                                            endpointPath: '/templates',
                                            get fullEndpoint() {
                                                return '<?php echo e(config('app.url')); ?>/api/v1/<?php echo e($subdomain); ?>' + this.endpointPath;
                                            },
                                            setEndpoint(path) {
                                                this.endpointPath = path;
                                            },
                                            copyExample() {
                                                const text = this.fullEndpoint;
                                                if (!text) {
                                                    showNotification('No text found to copy', 'danger');
                                                    return;
                                                }
                                                copyToClipboard(text);
                                                this.copiedExample = true;
                                                setTimeout(() => this.copiedExample = false, 2000);
                                            }
                                        }" @set-endpoint.window="setEndpoint($event.detail)">
                                        <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'example_endpoint','type' => 'text','class' => 'flex-1 w-full','xBind:value' => 'fullEndpoint','readonly' => true,'xRef' => 'exampleEndpoint']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'example_endpoint','type' => 'text','class' => 'flex-1 w-full','x-bind:value' => 'fullEndpoint','readonly' => true,'x-ref' => 'exampleEndpoint']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginal36263f9a6b42b4504b8be98f2116ea00 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.secondary','data' => ['xOn:click' => 'copyExample()','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:click' => 'copyExample()','class' => 'w-full sm:w-auto']); ?>
                                            <span x-text="copiedExample ? 'Copied' : 'Copy'"><?php echo e(t('copy')); ?></span>
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $attributes = $__attributesOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__attributesOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00)): ?>
<?php $component = $__componentOriginal36263f9a6b42b4504b8be98f2116ea00; ?>
<?php unset($__componentOriginal36263f9a6b42b4504b8be98f2116ea00); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Token Abilities Section - UPDATE ALL CLICKABLE SPANS -->
                            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                 <?php $__env->slot('content', null, []); ?> 
                                    <h3 class="text-lg font-semibold text-secondary-900 dark:text-white mb-2">
                                        <?php echo e(t('token_abilities')); ?></h3>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-6">
                                        <?php echo e(t('these_are_the_default_permissions_for_api_access')); ?>

                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-4 border-b dark:border-secondary-700 p-3">
                                                <?php echo e(t('contacts')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/contacts')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800 hover:bg-primary-200 transition-colors">
                                                    <?php echo e(t('contacts_create')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/contacts')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('contacts_read')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/contacts/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-warning-100 text-warning-800 hover:bg-warning-200 transition-colors">
                                                    <?php echo e(t('contacts_update')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/contacts/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-danger-100 text-danger-800 hover:bg-danger-200 transition-colors">
                                                    <?php echo e(t('contacts_delete')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('statuses')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/statuses')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800 hover:bg-primary-200 transition-colors">
                                                    <?php echo e(t('status_create')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/statuses')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('status_read')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/statuses/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-warning-100 text-warning-800 hover:bg-warning-200 transition-colors">
                                                    <?php echo e(t('status_update')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/statuses/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-danger-100 text-danger-800 hover:bg-danger-200 transition-colors">
                                                    <?php echo e(t('status_delete')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('sources')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/sources')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800 hover:bg-primary-200 transition-colors">
                                                    <?php echo e(t('source_create')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/sources')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('source_read')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/sources/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-warning-100 text-warning-800 hover:bg-warning-200 transition-colors">
                                                    <?php echo e(t('source_update')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/sources/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-danger-100 text-danger-800 hover:bg-danger-200 transition-colors">
                                                    <?php echo e(t('source_delete')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('templates')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/templates')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('template_read')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('message_bots')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/message-bots')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('message_bot_read')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('template_bots')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/template-bots')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('template_bot_read')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3">
                                                <?php echo e(t('groups')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/groups')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800 hover:bg-primary-200 transition-colors">
                                                    <?php echo e(t('group_create')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/groups')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors">
                                                    <?php echo e(t('group_read')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/groups/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-warning-100 text-warning-800 hover:bg-warning-200 transition-colors">
                                                    <?php echo e(t('group_update')); ?>

                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/groups/{id}')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-danger-100 text-danger-800 hover:bg-danger-200 transition-colors">
                                                    <?php echo e(t('group_delete')); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-white dark:bg-secondary-800 border dark:border-secondary-700 rounded-lg shadow-sm">
                                            <h4
                                                class="text-sm font-semibold text-secondary-700 dark:text-secondary-300 mb-3 border-b p-3 flex items-center gap-1">
                                                <?php echo e(t('message_sending')); ?>

                                            </h4>
                                            <div class="flex flex-wrap gap-2 px-5 pb-3">
                                                <span @click="$dispatch('set-endpoint', '/messages/send')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-primary-100 text-primary-800 hover:bg-primary-200 transition-colors flex items-center gap-1">
                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-question-mark-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 cursor-pointer','data-tippy-content' => ''.e(t('sending_message_limit_alert')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                                    <span><?php echo e(t('simple_message_send')); ?> </span>
                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/messages/media')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-success-100 text-success-800 hover:bg-success-200 transition-colors flex items-center gap-1">
                                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-question-mark-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 cursor-pointer','data-tippy-content' => ''.e(t('sending_message_limit_alert')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                                                    <span><?php echo e(t('media_message_send')); ?> </span>
                                                </span>
                                                <span @click="$dispatch('set-endpoint', '/messages/template')"
                                                    class="cursor-pointer px-2 py-1 rounded text-xs font-medium bg-warning-100 text-warning-800 hover:bg-warning-200 transition-colors">
                                                    <?php echo e(t('template_message_send')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                 <?php $__env->endSlot(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
                            <div class="rounded-md bg-success-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-success-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-success-800">
                                            <?php echo e(session('Success')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                     <?php $__env->endSlot(); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('system_settings.edit')): ?>
                     <?php $__env->slot('footer', null, ['class' => 'bg-slate-50 dark:bg-transparent rounded-b-lg p-4']); ?> 
                        <div class="flex justify-end items-center">
                            <?php if (isset($component)) { $__componentOriginal533f51d0b2818acbd35337da747efa74 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal533f51d0b2818acbd35337da747efa74 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.loading-button','data' => ['type' => 'submit','target' => 'save']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.loading-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','target' => 'save']); ?>
                                <?php echo e(t('save_changes_button')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal533f51d0b2818acbd35337da747efa74)): ?>
<?php $attributes = $__attributesOriginal533f51d0b2818acbd35337da747efa74; ?>
<?php unset($__attributesOriginal533f51d0b2818acbd35337da747efa74); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal533f51d0b2818acbd35337da747efa74)): ?>
<?php $component = $__componentOriginal533f51d0b2818acbd35337da747efa74; ?>
<?php unset($__componentOriginal533f51d0b2818acbd35337da747efa74); ?>
<?php endif; ?>
                        </div>
                     <?php $__env->endSlot(); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
            </form>
        </div>
    </div>
</div><?php /**PATH /home/u108339042/domains/dash.chatvoo.com/public_html/Modules/ApiWebhookManager/resources/views/livewire/tenant/settings/system/manage-api-tokens.blade.php ENDPATH**/ ?>