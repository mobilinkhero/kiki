<!-- Email Templates List View (templates.blade.php) -->
<div>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(t('email_templates')); ?>

     <?php $__env->endSlot(); ?>

       <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
        ['label' => t('email_templates')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
        ['label' => t('email_templates')],
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>

    
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
      <?php $__env->slot('content', null, []); ?> 
    <!-- Templates grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white dark:bg-slate-800 rounded-lg ring-1 ring-slate-300 dark:ring-slate-600 overflow-hidden hover:shadow-md transition-shadow duration-300">
            <!-- Header with name and status -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 truncate"><?php echo e($template->name); ?></h3>
                <span
                    class="px-2 py-1 text-xs rounded-full <?php echo e($template->is_active ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800'); ?>">
                    <?php echo e($template->is_active ? t('active') : t('inactive')); ?>

                </span>
            </div>

            <!-- Template details -->
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <div>
                        <p class="text-xs text-gray-500"><?php echo e(t('category')); ?></p>
                        <p class="text-sm font-medium text-gray-700"><?php echo e($template->category ?: 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500"><?php echo e(t('type')); ?></p>
                        <p class="text-sm font-medium text-gray-700"><?php echo e($template->type ?: 'N/A'); ?></p>
                    </div>
                </div>

                <div class="mb-2">
                    <p class="text-xs text-gray-500"><?php echo e(t('subject')); ?></p>
                    <p class="text-sm font-medium text-gray-700 truncate"><?php echo e($template->subject); ?></p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($template->description): ?>
                <div class="mb-2">
                    <p class="text-xs text-gray-500"><?php echo e(t('description')); ?></p>
                    <p class="text-sm text-gray-700 line-clamp-2"><?php echo e($template->description); ?></p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex items-center space-x-2 mt-3">
                    <span
                        class="text-xs px-2 py-1 rounded-full <?php echo e($template->is_system ? 'bg-info-100 text-info-800' : 'bg-purple-100 text-purple-800'); ?>">
                        <?php echo e($template->is_system ? t('system') : t('custom')); ?>

                    </span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($template->use_layout): ?>
                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                        <?php echo e(t('with_layout')); ?>

                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Action button -->
            <div class="border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.email_template.edit')): ?>
                <a href="<?php echo e(tenant_route('tenant.emails.save', ["id"=> $template->id])); ?>" class="block w-full
                    text-center py-3 text-sm font-medium text-primary-600 hover:bg-gray-50 transition-colors">
                    <?php echo e(t('view_template')); ?>

                </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-3 bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500"><?php echo e(t('no_templates_found')); ?></p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
</div><?php /**PATH /home/u108339042/domains/dash.chatvoo.com/public_html/resources/views/livewire/tenant/email-template/email-template-list.blade.php ENDPATH**/ ?>