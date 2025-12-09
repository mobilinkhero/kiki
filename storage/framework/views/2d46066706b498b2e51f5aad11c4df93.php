<div x-data="{
    view: localStorage.getItem('contacts_view') || 'list',
    init() {
      // Persist to localStorage
      this.$watch('view', v => localStorage.setItem('contacts_view', v));

      // Init tooltip on the stable element (the button)
      if (window.tippy && $refs.viewToggle) {
        tippy($refs.viewToggle, { allowHTML: true, content: this.view === 'list' ? '<?php echo e(t('swith_to_kanban')); ?>' : '<?php echo e(t('swith_to_list')); ?>' });
      }
    }
  }" x-init="init()" class="relative space-y-4">
   <?php $__env->slot('title', null, []); ?> 
    <?php echo e(t('contact')); ?>

   <?php $__env->endSlot(); ?>

       <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
        ['label' => t('contact')],
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => t('dashboard'), 'route' => tenant_route('tenant.dashboard')],
        ['label' => t('contact')],
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

  <div class="flex flex-col sm:flex-row justify-between items-start lg:items-center gap-2">
    <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-3 lg:mb-2">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.contact.create')): ?>
      <?php if (isset($component)) { $__componentOriginal79c47ff43af68680f280e55afc88fe59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c47ff43af68680f280e55afc88fe59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.primary','data' => ['href' => ''.e(tenant_route('tenant.contacts.save')).'','wire:click' => 'createContact']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.primary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(tenant_route('tenant.contacts.save')).'','wire:click' => 'createContact']); ?>
        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-m-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?><?php echo e(t('new_contact_button')); ?>

       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c47ff43af68680f280e55afc88fe59)): ?>
<?php $attributes = $__attributesOriginal79c47ff43af68680f280e55afc88fe59; ?>
<?php unset($__attributesOriginal79c47ff43af68680f280e55afc88fe59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c47ff43af68680f280e55afc88fe59)): ?>
<?php $component = $__componentOriginal79c47ff43af68680f280e55afc88fe59; ?>
<?php unset($__componentOriginal79c47ff43af68680f280e55afc88fe59); ?>
<?php endif; ?>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.contact.bulk_import')): ?>
      <?php if (isset($component)) { $__componentOriginal79c47ff43af68680f280e55afc88fe59 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79c47ff43af68680f280e55afc88fe59 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.primary','data' => ['href' => ''.e(tenant_route('tenant.contacts.import_log')).'','wire:click' => 'importContact']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.primary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(tenant_route('tenant.contacts.import_log')).'','wire:click' => 'importContact']); ?>
        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-m-plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?><?php echo e(t('import_contact')); ?>

       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79c47ff43af68680f280e55afc88fe59)): ?>
<?php $attributes = $__attributesOriginal79c47ff43af68680f280e55afc88fe59; ?>
<?php unset($__attributesOriginal79c47ff43af68680f280e55afc88fe59); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79c47ff43af68680f280e55afc88fe59)): ?>
<?php $component = $__componentOriginal79c47ff43af68680f280e55afc88fe59; ?>
<?php unset($__componentOriginal79c47ff43af68680f280e55afc88fe59); ?>
<?php endif; ?>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      <!-- View toggle -->
      <div class="flex justify-end relative group">
        <button x-ref="viewToggle" @click="
            view = (view === 'list') ? 'kanban' : 'list';
            $nextTick(() => { if ($refs.viewToggle && $refs.viewToggle._tippy) { $refs.viewToggle._tippy.setContent(view === 'list' ? '<?php echo e(t('swith_to_kanban')); ?>' : '<?php echo e(t('swith_to_list')); ?>'); }});
          " class="inline-flex items-center justify-center px-4 py-2 text-sm border border-transparent rounded-md font-medium disabled:opacity-50 disabled:pointer-events-none transition text-white bg-primary-600 hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
          aria-label="<?php echo e(t('toggle_view')); ?>">
          <!-- List Icon -->
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-bars-3'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'view === \'list\'','x-cloak' => true,'class' => 'w-5 h-5 text-white dark:text-gray-300']); ?>
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
          <!-- Kanban Icon -->
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-view-columns'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'view === \'kanban\'','x-cloak' => true,'class' => 'w-5 h-5 text-white dark:text-gray-300']); ?>
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
        </button>
        <!-- CSS Tooltip -->
        <div
          class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 dark:bg-gray-700 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
          <span
            x-text="view === 'list' ? '<?php echo e(t('switch_to_kanban') ?? 'Switch to Kanban'); ?>' : '<?php echo e(t('switch_to_list') ?? 'Switch to List'); ?>'"></span>
          <!-- Tooltip Arrow -->
          <div
            class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-gray-900 dark:border-t-gray-700">
          </div>
        </div>
      </div>
    </div>

    <!-- Feature Limit Badge -->
    <div class="mb-2">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($this->isUnlimited) && $this->isUnlimited): ?>
      <?php if (isset($component)) { $__componentOriginal2474b3053492ad88b606267c9b7ef95d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2474b3053492ad88b606267c9b7ef95d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.unlimited-badge','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('unlimited-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(t('unlimited')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2474b3053492ad88b606267c9b7ef95d)): ?>
<?php $attributes = $__attributesOriginal2474b3053492ad88b606267c9b7ef95d; ?>
<?php unset($__attributesOriginal2474b3053492ad88b606267c9b7ef95d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2474b3053492ad88b606267c9b7ef95d)): ?>
<?php $component = $__componentOriginal2474b3053492ad88b606267c9b7ef95d; ?>
<?php unset($__componentOriginal2474b3053492ad88b606267c9b7ef95d); ?>
<?php endif; ?>
      <?php elseif(isset($this->remainingLimit) && isset($this->totalLimit)): ?>
      <?php if (isset($component)) { $__componentOriginal6261997bb4bcd12eea01202754e1db02 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6261997bb4bcd12eea01202754e1db02 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.remaining-limit-badge','data' => ['label' => ''.e(t('remaining')).'','value' => $this->remainingLimit,'count' => $this->totalLimit]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('remaining-limit-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => ''.e(t('remaining')).'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->remainingLimit),'count' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->totalLimit)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6261997bb4bcd12eea01202754e1db02)): ?>
<?php $attributes = $__attributesOriginal6261997bb4bcd12eea01202754e1db02; ?>
<?php unset($__attributesOriginal6261997bb4bcd12eea01202754e1db02); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6261997bb4bcd12eea01202754e1db02)): ?>
<?php $component = $__componentOriginal6261997bb4bcd12eea01202754e1db02; ?>
<?php unset($__componentOriginal6261997bb4bcd12eea01202754e1db02); ?>
<?php endif; ?>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
  </div>

  <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['xShow' => 'view === \'list\'','class' => 'rounded-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'view === \'list\'','class' => 'rounded-lg']); ?>
     <?php $__env->slot('content', null, []); ?> 
      <!-- List View -->
      <div x-show="view === 'list'" x-cloak class="lg:mt-0" wire:poll.30s="refreshTable">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tenant.tables.contact-table', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-984998523-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
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
  <div x-show="view === 'kanban'" x-cloak class="lg:mt-0">
    
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tenant.contact.contact-kanban', []);

$key = 'kanban';

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-984998523-1', 'kanban');

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
  </div>
  <!-- Delete Confirmation Modal -->
  <?php if (isset($component)) { $__componentOriginal79e52b819ddc9a73b4560c41923d18f7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal79e52b819ddc9a73b4560c41923d18f7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal.confirm-box','data' => ['maxWidth' => 'lg','id' => 'delete-contact-modal','title' => ''.e(t('delete_contact_title')).'','wire:model.defer' => 'confirmingDeletion','description' => ''.e(t('delete_message')).' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal.confirm-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['maxWidth' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('lg'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('delete-contact-modal'),'title' => ''.e(t('delete_contact_title')).'','wire:model.defer' => 'confirmingDeletion','description' => ''.e(t('delete_message')).' ']); ?>
    <div
      class="border-neutral-200 border-neutral-500/30 flex justify-end items-center sm:block space-x-3 bg-gray-100 dark:bg-gray-700 ">
      <?php if (isset($component)) { $__componentOriginalae37219fcdee25763f87d04348a96c20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae37219fcdee25763f87d04348a96c20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.cancel-button','data' => ['wire:click' => '$set(\'confirmingDeletion\', false)','class' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.cancel-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => '$set(\'confirmingDeletion\', false)','class' => '']); ?>
        <?php echo e(t('cancel')); ?>

       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae37219fcdee25763f87d04348a96c20)): ?>
<?php $attributes = $__attributesOriginalae37219fcdee25763f87d04348a96c20; ?>
<?php unset($__attributesOriginalae37219fcdee25763f87d04348a96c20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae37219fcdee25763f87d04348a96c20)): ?>
<?php $component = $__componentOriginalae37219fcdee25763f87d04348a96c20; ?>
<?php unset($__componentOriginalae37219fcdee25763f87d04348a96c20); ?>
<?php endif; ?>
      <?php if (isset($component)) { $__componentOriginal254f851538d10c3f8455184bad85911f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal254f851538d10c3f8455184bad85911f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.delete-button','data' => ['wire:click' => 'delete','class' => 'mt-3 sm:mt-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.delete-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:click' => 'delete','class' => 'mt-3 sm:mt-0']); ?>
        <?php echo e(t('delete')); ?>

       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal254f851538d10c3f8455184bad85911f)): ?>
<?php $attributes = $__attributesOriginal254f851538d10c3f8455184bad85911f; ?>
<?php unset($__attributesOriginal254f851538d10c3f8455184bad85911f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal254f851538d10c3f8455184bad85911f)): ?>
<?php $component = $__componentOriginal254f851538d10c3f8455184bad85911f; ?>
<?php unset($__componentOriginal254f851538d10c3f8455184bad85911f); ?>
<?php endif; ?>
    </div>
   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal79e52b819ddc9a73b4560c41923d18f7)): ?>
<?php $attributes = $__attributesOriginal79e52b819ddc9a73b4560c41923d18f7; ?>
<?php unset($__attributesOriginal79e52b819ddc9a73b4560c41923d18f7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal79e52b819ddc9a73b4560c41923d18f7)): ?>
<?php $component = $__componentOriginal79e52b819ddc9a73b4560c41923d18f7; ?>
<?php unset($__componentOriginal79e52b819ddc9a73b4560c41923d18f7); ?>
<?php endif; ?>

  
  <?php if (isset($component)) { $__componentOriginal8050579086355a6ef9a782e9f44d533f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8050579086355a6ef9a782e9f44d533f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal.custom-modal','data' => ['id' => 'view-contact-modal','maxWidth' => '5xl','wire:model.defer' => 'viewContactModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal.custom-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('view-contact-modal'),'maxWidth' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('5xl'),'wire:model.defer' => 'viewContactModal']); ?>
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-500/30 flex justify-between">
      <h1 class="text-xl font-medium text-slate-800 dark:text-slate-300">
        <?php echo e($contact ? "#{$contact->id} - {$contact->firstname} {$contact->lastname}" : t('contact_details')); ?>

      </h1>
      <button class="text-gray-500 hover:text-gray-700 text-2xl dark:hover:text-gray-300"
        wire:click="$set('viewContactModal', false)">
        &times;
      </button>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'profile' }">
      <div
        class="bg-gray-100 border-b border-neutral-200 dark:bg-gray-800 dark:border-neutral-500/30 gap-2 grid  grid-cols-3 mt-5 mx-5 px-2 py-1.5 rounded-md">

        <!-- Profile Tab -->
        <button class="px-4 py-2 text-sm font-medium rounded-md flex items-center justify-center space-x-2" :class="activeTab === 'profile'
                        ?
                        'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400' :
                        'text-gray-600 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400'"
          x-on:click="activeTab = 'profile'">
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden md:inline w-6 h-6']); ?>
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
          <span> <?php echo e(t('profile')); ?> </span>
        </button>

        <!-- Other Information Tab -->
        <button class="px-4 py-2 text-sm font-medium rounded-md flex items-center justify-center space-x-2" :class="activeTab === 'other'
                        ?
                        'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400' :
                        'text-gray-600 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400'"
          x-on:click="activeTab = 'other'">
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-information-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden md:inline w-6 h-6']); ?>
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
          <span> <?php echo e(t('other_information_contact')); ?> </span>
        </button>

        <!-- Notes Tab -->
        <button class="px-4 py-2 text-sm font-medium rounded-md flex items-center justify-center space-x-2" :class="activeTab === 'notes'
                        ?
                        'bg-white dark:bg-gray-700 text-primary-600 dark:text-primary-400' :
                        'text-gray-600 dark:text-gray-300 hover:text-primary-500 dark:hover:text-primary-400'"
          x-on:click="activeTab = 'notes'">
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-document-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden md:inline w-6 h-6']); ?>
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
          <span> <?php echo e(t('notes_title')); ?> </span>
        </button>
      </div>

      <div class="p-4">
        <div x-show="activeTab === 'profile'">
          <div class="grid grid-cols-2 gap-x-8 gap-y-4 p-4 rounded-lg break-words">
            <div class="space-y-4">
              <!-- Name -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"><?php echo e(t('name')); ?></span>
                <p class="text-sm text-slate-700 dark:text-slate-300 tesxt-wrap">
                  <?php echo e($contact ? "{$contact->firstname} {$contact->lastname}" : '-'); ?>

                </p>
              </div>

              <!-- Status -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('status')); ?>

                </span>
                <div>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    style="background-color: <?php echo e($contact->status->color ?? '#ccc'); ?>20; color: <?php echo e($contact->status->color ?? '#333'); ?>;">
                    <?php echo e($contact->status->name ?? '-'); ?>

                  </span>
                </div>
              </div>

              <!-- Source -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('source')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e($contact->source->name ?? '-'); ?></p>
              </div>

              <!-- Assigned -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('assigned')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e($contact && $contact->user ? "{$contact->user->firstname}
                  {$contact->user->lastname}" : '-'); ?>

                </p>
              </div>

              <!-- Company -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('company')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(isset($contact) && $contact->company ? $contact->company : '-'); ?>

                </p>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Type -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('type')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(ucfirst($contact->type ?? '-')); ?></p>
              </div>

              <!-- Email -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('email')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300 ">
                  <?php echo e(isset($contact) && $contact->email ? $contact->email : '-'); ?></p>
              </div>

              <!-- Phone -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"><?php echo e(t('phone')); ?></span>
                <p>
                  <a href='tel:<?php echo e($contact->phone ?? ' -'); ?>' class="text-info-600 text-sm">
                    <?php echo e($contact->phone ?? '-'); ?>

                  </a>
                </p>
              </div>

              <!-- Website -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('website')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(isset($contact) && $contact->website ? $contact->website : '-'); ?></p>

              </div>

            </div>
          </div>

          <!-- Custom Fields Section -->
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customFields && $customFields->count() > 0): ?>
          <div class="mt-4 pt-6 border-t border-gray-200 dark:border-gray-600 px-4">
            <div class="items-center px-3 py-1.5 rounded-md bg-gray-100 dark:bg-gray-700 mb-4 text-center">
              <h3 class="text-sm font-medium text-slate-700 dark:text-slate-300"><?php echo e(t('custom_fields')); ?></h3>
            </div>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4 break-words">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customFieldData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $field = $customFieldData['field'];
                  $value = $customFieldData['display_value'];
                ?>
                <div>
                  <span class="text-sm text-slate-400 dark:text-slate-400"><?php echo e($field->field_label); ?></span>
                  <p class="text-sm text-slate-700 dark:text-slate-300 break-words">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field->field_type === 'checkbox'): ?>
                      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($value): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                          <?php echo e(t('yes')); ?>

                        </span>
                      <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                          <?php echo e(t('no')); ?>

                        </span>
                      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php elseif($field->field_type === 'date' && $value): ?>
                      <?php echo e(\Carbon\Carbon::parse($value)->format('M d, Y')); ?>

                    <?php elseif($value): ?>
                      <?php echo e($value); ?>

                    <?php else: ?>
                      <span class="text-gray-400 dark:text-gray-500">-</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  </p>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div x-show="activeTab === 'other'">
          <div class="grid grid-cols-2 gap-x-8 gap-y-4 p-4 rounded-lg">
            <div class="space-y-4">
              <!-- City -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('city')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(isset($contact) && $contact->city ? $contact->city : '-'); ?>

                </p>
              </div>

              <!-- State -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('state')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(isset($contact) && $contact->state ? $contact->state : '-'); ?>

                </p>
              </div>

              <!-- Country -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('country')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(get_country_name($contact->country_id) ? get_country_name($contact->country_id) :
                  '-'); ?>

                </p>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Zip Code -->
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('zip_code')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300">
                  <?php echo e(isset($contact) && $contact->zip ? $contact->zip : '-'); ?>

                </p>
              </div>
              <div>
                <span class=" text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('description')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300 break-words">
                  <?php echo e(isset($contact) && $contact->description ? $contact->description : '-'); ?>

                </p>
              </div>

              <!-- Address -->
              <div>
                <span class="text-sm text-slate-400 dark:text-slate-400"> <?php echo e(t('address')); ?>

                </span>
                <p class="text-sm text-slate-700 dark:text-slate-300 break-words">
                  <?php echo e(isset($contact) && $contact->address ? $contact->address : '-'); ?>

                </p>
              </div>
            </div>
          </div>
        </div>

        <div x-show="activeTab === 'notes'">
          <div class="col-span-1">
            <div>
              <div
                class="mt-4 relative px-4 h-80 overflow-y-auto scrollbar-thin scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-800">
                <ol class="relative border-s border-gray-300 dark:border-gray-700">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <li class="mb-6 ms-4 relative">
                    <div class="absolute w-2 h-2 bg-primary-600 dark:bg-primary-400 rounded-full -left-5 top-4">
                    </div>

                    <div class="flex-1 p-2 border-b border-gray-300 dark:border-gray-600 text-sm space-y-1">

                      <span class="text-xs text-gray-500 dark:text-gray-400 block relative"
                        data-tippy-content="<?php echo e(format_date_time($note['created_at'])); ?>"
                        style="cursor: pointer; display: inline-block; text-decoration: underline dotted;">
                        <?php echo e(\Carbon\Carbon::parse($note['created_at'])->diffForHumans(['options'
                        => \Carbon\Carbon::JUST_NOW])); ?>

                      </span>
                      <div class="flex justify-between items-start flex-nowrap">
                        <span class="text-gray-800 dark:text-gray-200 flex-1">
                          <?php echo e($note['notes_description']); ?>

                        </span>
                      </div>
                    </div>
                  </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <p class="text-gray-500 dark:text-gray-400 text-center">
                    <?php echo e(t('no_notes_available')); ?> </p>
                  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8050579086355a6ef9a782e9f44d533f)): ?>
<?php $attributes = $__attributesOriginal8050579086355a6ef9a782e9f44d533f; ?>
<?php unset($__attributesOriginal8050579086355a6ef9a782e9f44d533f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8050579086355a6ef9a782e9f44d533f)): ?>
<?php $component = $__componentOriginal8050579086355a6ef9a782e9f44d533f; ?>
<?php unset($__componentOriginal8050579086355a6ef9a782e9f44d533f); ?>
<?php endif; ?>
  <!-- intial chat Modal -->
  <div x-data="{
      modalSize: 'max-w-6xl',
      isOpen: <?php if ((object) ('showInitiateChatModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showInitiateChatModal'->value()); ?>')<?php echo e('showInitiateChatModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showInitiateChatModal'); ?>')<?php endif; ?>,
      campaignsSelected: false,
      fileError: null,
      isDisabled: false,
      campaignHeader: '',
      isSaving: false,
      campaignBody: '',
      campaignFooter: '',
      buttons: [],
      inputType: 'text',
      inputAccept: '',
      headerInputs: <?php if ((object) ('headerInputs') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('headerInputs'->value()); ?>')<?php echo e('headerInputs'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('headerInputs'); ?>')<?php endif; ?>,
      bodyInputs: <?php if ((object) ('bodyInputs') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('bodyInputs'->value()); ?>')<?php echo e('bodyInputs'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('bodyInputs'); ?>')<?php endif; ?>,
      footerInputs: <?php if ((object) ('footerInputs') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('footerInputs'->value()); ?>')<?php echo e('footerInputs'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('footerInputs'); ?>')<?php endif; ?>,
      mergeFields: <?php if ((object) ('mergeFields') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mergeFields'->value()); ?>')<?php echo e('mergeFields'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('mergeFields'); ?>')<?php endif; ?>,
      editTemplateId: <?php if ((object) ('template_id') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('template_id'->value()); ?>')<?php echo e('template_id'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('template_id'); ?>')<?php endif; ?>,
      headerInputErrors: [],
      bodyInputErrors: [],
      footerInputErrors: [],
      headerParamsCount: 0,
      bodyParamsCount: 0,
      footerParamsCount: 0,
      selectedCount: 0,
      relType: '',
      previewUrl: '<?php echo e(!empty($filename) ? asset('storage/' . $filename) : ''); ?>', // Added for preview
      previewType: '', // Store file type (image, video, document)
      previewFileName: '<?php echo e(!empty($filename) ? basename($filename) : ''); ?>',
      filteredContacts: <?php if ((object) ('contacts') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('contacts'->value()); ?>')<?php echo e('contacts'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('contacts'); ?>')<?php endif; ?>,
      metaExtensions: <?php echo e(json_encode(get_meta_allowed_extension())); ?>,
      isUploading: false,
      progress: 0,
      resetModal() {
          // Reset all preview and form data
          if (document.getElementById('basic-select')) {
              document.getElementById('basic-select').value = '';
          }
          // Reset Alpine data
          this.previewUrl = '';
          this.previewFileName = '';
          this.fileError = null;
          this.campaignsSelected = false;
          this.campaignHeader = '';
          this.campaignBody = '';
          this.campaignFooter = '';
          this.buttons = [];
          this.headerInputs = [];
          this.bodyInputs = [];
          this.footerInputs = [];

          // Reset Livewire data
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('template_id', '');
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('headerInputs', []);
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('bodyInputs', []);
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('footerInputs', []);
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('file', null);
          window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('filename', null);
      },
      uploadStarted() {
          this.isUploading = true;
          this.progress = 0;
          $dispatch('upload-started');
      },
      uploadFinished() {
          this.isUploading = false;
          this.progress = 100;
          $dispatch('upload-finished');
      },
      updateProgress(progress) {
          this.progress = progress;
      },
      handleTributeEvent() {

          setTimeout(() => {
            if (typeof window.Tribute === 'undefined') {
              return;
              }

              let tribute = new window.Tribute({
                  trigger: '@',
                  values: JSON.parse(this.mergeFields),
              });

              document.querySelectorAll('.mentionable').forEach((el) => {
                  if (!el.hasAttribute('data-tribute')) {
                      tribute.attach(el);
                      el.setAttribute('data-tribute', 'true'); // Mark as initialized
                  }
              });
          }, 2000);
      },
      initTribute() {
          this.$watch('mergeFields', (newValue) => {
              this.handleTributeEvent();
          });
          this.handleTributeEvent();
      },
      handleCampaignChange(event) {
          const selectedOption = event.target.selectedOptions[0];
          this.campaignsSelected = event.target.value !== '';
          this.campaignHeader = selectedOption?.dataset.header || '';
          this.campaignBody = selectedOption?.dataset.body || '';
          this.campaignFooter = selectedOption?.dataset.footer || '';
          this.buttons = selectedOption ? JSON.parse(selectedOption.dataset.buttons || '[]') : [];
          this.inputType = selectedOption?.dataset.headerFormat || 'text';
          this.headerParamsCount = parseInt(selectedOption?.dataset.headerParamsCount || 0);
          this.bodyParamsCount = parseInt(selectedOption?.dataset.bodyParamsCount || 0);
          this.footerParamsCount = parseInt(selectedOption?.dataset.footerParamsCount || 0);

          if (!selectedOption || !this.previewUrl.includes('<?php echo e($filename ?? ''); ?>')) {
              this.previewUrl = '';
              this.previewFileName = '';
          }

          const format = selectedOption?.dataset.headerFormat || 'text';
          this.inputAccept = this.metaExtensions[format.toLowerCase()]?.extension || '';

          if (selectedOption?.value != this.editTemplateId) {
              this.previewUrl = '';
              this.previewFileName = '';
              this.bodyInputs = [];
              this.footerInputs = [];
              this.headerInputs = [];
          }
      },

      replaceVariables(template, inputs) {
          if (!template || !inputs) return ''; // Prevent undefined error
          return template.replace(/\{\{(\d+)\}\}/g, (match, p1) => {
              const index = parseInt(p1, 10) - 1;
              return `<span class='text-indigo-600'>${inputs[index] || match}</span>`;
          });
      },
      handleFilePreview(event) {
          const file = event.target.files[0];
          this.fileError = null; // Clear previous errors

          if (!file) {
              return;
          }

          // Get allowed extensions and max size from metaExtensions
          const typeKey = this.inputType.toLowerCase(); // Convert to lowercase for consistency
          const metaData = this.metaExtensions[typeKey];


          const allowedExtensions = metaData.extension.split(',').map(ext => ext.trim());
          const maxSizeMB = metaData.size || 0; // Default to 0 if not set
          const maxSizeBytes = maxSizeMB * 1024 * 1024; // Convert MB to bytes

          // Extract file extension
          const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

          // Validate file extension (from metaExtensions)
          if (!allowedExtensions.includes(fileExtension)) {
              this.fileError = `Invalid file type. Allowed types: ${allowedExtensions.join(', ')}`;
              return;
          }

          // MIME type validation (strict check)
          const fileType = file.type.split('/')[0];

          if (this.inputType === 'DOCUMENT' && !['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'text/plain'].includes(file.type)) {
              this.fileError = 'Invalid document type. Please upload a valid document.';
              return;
          }

          if (this.inputType === 'IMAGE' && !file.type.startsWith('image/')) {
              this.fileError = 'Invalid image file. Please upload an image.';
              return;
          }

          if (this.inputType === 'VIDEO' && !file.type.startsWith('video/')) {
              this.fileError = 'Invalid video file. Please upload a video.';
              return;
          }

          if (this.inputType === 'AUDIO' && !file.type.startsWith('audio/')) {
              this.fileError = 'Invalid audio file. Please upload an audio file.';
              return;
          }

          if (this.inputType === 'STICKER' && file.type !== 'image/webp') {
              this.fileError = 'Invalid sticker file. Only .webp format is allowed.';
              return;
          }

          // Validate file size
          if (file.size > maxSizeBytes) {
              this.fileError = `File size exceeds ${maxSizeMB} MB. Please upload a smaller file.`;
              return;
          }

          // If validation passes, handle the file preview
          this.previewUrl = URL.createObjectURL(file);
          this.previewFileName = file.name;
      },
      validateInputs() {
          const hasTextInputs = this.headerParamsCount > 0 || this.bodyParamsCount > 0 || this.footerInputs.length > 0;
          const hasFileInput = ['IMAGE', 'VIDEO', 'DOCUMENT', 'AUDIO'].includes(this.inputType);

          if (!hasTextInputs && !hasFileInput) {
              return true;
          }
          const validateInputGroup = (inputs, paramsCount) => {
              // Ensure inputs is a properly unwrapped array
              const unwrappedInputs = inputs ? JSON.parse(JSON.stringify(inputs)) : [];

              // Ensure length matches paramsCount by filling missing values with empty strings
              while (unwrappedInputs.length < paramsCount) {
                  unwrappedInputs.push('');
              }

              // Return errors if inputs are empty
              return unwrappedInputs.map(value =>
                  value.trim() === '' ? '<?php echo e(t('this_field_is_required')); ?>' : ''
              );
          };

          // Validate text inputs
          this.headerInputErrors = validateInputGroup(this.headerInputs, this.headerParamsCount);
          this.bodyInputErrors = validateInputGroup(this.bodyInputs, this.bodyParamsCount);
          this.footerInputErrors = validateInputGroup(this.footerInputs, this.footerInputs.length);

          if (hasFileInput && !this.previewFileName) {
              this.fileError = '<?php echo e(t('this_field_is_required')); ?>';
          } else {
              this.fileError = ''; // Reset file error if not needed
          }

          // Check if all inputs are valid
          const isTextValid = [this.headerInputErrors, this.bodyInputErrors, this.footerInputErrors]
              .every(errors => errors.length === 0 || errors.every(error => error === ''));

          const isFileValid = !this.fileError; // No error means file validation passed

          return isTextValid && isFileValid;
      },

      handleSave() {

          const isValid = this.validateInputs();
          if (!isValid) return; // Stop if validation fails
          $wire.save();
          setTimeout(() => {
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('template_id', '');
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('headerInputs', []);
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('bodyInputs', []);
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('footerInputs', []);
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('file', null);
              window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('filename', null);
          }, 500)


      }

   }" x-init="$nextTick(() => {
      const select = $el.querySelector('#basic-select');

      if (select?.value) {
          handleCampaignChange({ target: select });
      }
   })" x-on:open-modal.window="isOpen = true" x-on:keydown.escape.window="isOpen = false;resetModal()"
    x-effect="modalSize = campaignsSelected ? 'max-w-6xl' : 'max-w-2xl'">
    <template x-if="isOpen">
      <div class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <!-- Backdrop with Gradient - with click handler to close modal -->
        <div class="fixed inset-0 backdrop-blur-sm bg-gradient-to-br from-black/30 to-black/60"
          x-on:click="isOpen = false;resetModal()" x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
          x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0">
        </div>

        <!-- Modal Container with Animation - slide from top -->
        <div class="flex items-start justify-center p-4 pt-20">
          <div x-show="isOpen" @click.stop x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-10"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-10" :class="modalSize"
            class="relative w-full overflow-hidden rounded-2xl bg-white/95 dark:bg-slate-800/95 shadow-2xl ring-1 ring-black/5 dark:ring-white/5 transition-all duration-300">
            <!-- Added transition -->

            <!-- Gradient Background Accent -->
            <div
              class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-transparent to-purple-50/50 dark:from-indigo-900/10 dark:to-purple-900/10">
            </div>

            <!-- Content Container -->
            <div class="relative">
              <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-500/30 flex justify-between">
                <h1 class="text-xl font-medium text-slate-800 dark:text-slate-300">
                  <?php echo e(t('initiate_chat')); ?>

                </h1>

                <button class="text-gray-500 hover:text-gray-700 text-2xl dark:hover:text-gray-300"
                  x-on:click="isOpen = false;resetModal()">
                  &times;
                </button>
              </div>
              <div class="px-6 py-4">
                <form wire:submit.prevent="save">
                  <!-- Template selection first - always visible -->
                  <div class="mb-6">
                    <div class="flex item-centar justify-start">
                      <span class="text-red-500 me-1 ">*</span>
                      <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'template_id','value' => t('template')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'template_id','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(t('template'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                    </div>
                    <div wire:ignore x-cloak>
                      <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'basic-select','class' => 'tom-select mt-1 block w-full ','wire:model.defer' => 'template_id','xRef' => 'campaignsChange','xOn:change' => 'handleCampaignChange({ target: $refs.campaignsChange });','xInit' => '() => {
                            handleCampaignChange({ target: $refs.campaignsChange });
                        }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'basic-select','class' => 'tom-select mt-1 block w-full ','wire:model.defer' => 'template_id','x-ref' => 'campaignsChange','x-on:change' => 'handleCampaignChange({ target: $refs.campaignsChange });','x-init' => '() => {
                            handleCampaignChange({ target: $refs.campaignsChange });
                        }']); ?>
                        <option value="" selected><?php echo e(t('nothing_selected')); ?>

                        </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($template['template_id']); ?>" data-header="<?php echo e($template['header_data_text']); ?>"
                          data-body="<?php echo e($template['body_data']); ?>" data-footer="<?php echo e($template['footer_data']); ?>"
                          data-buttons="<?php echo e($template['buttons_data']); ?>"
                          data-header-format="<?php echo e($template['header_data_format']); ?>"
                          data-header-params-count="<?php echo e($template['header_params_count']); ?>"
                          data-body-params-count="<?php echo e($template['body_params_count']); ?>"
                          data-footer-params-count="<?php echo e($template['footer_params_count']); ?>">
                          <?php echo e($template['template_name'] . ' (' . $template['language'] . ')'); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                    </div>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['for' => 'template_id','class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'template_id','class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                  </div>

                  <!-- Two-column layout when template is selected -->
                  <div x-show="campaignsSelected" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left column: Variables -->
                    <div>
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
                          <h1 class="text-xl font-semibold text-slate-700 dark:text-slate-300">
                            <?php echo e(t('variables')); ?>

                          </h1>
                         <?php $__env->endSlot(); ?>
                         <?php $__env->slot('content', null, []); ?> 
                          <div>
                            <!-- Alert for missing variables -->
                            <div
                              x-show="((inputType == 'TEXT' || inputType == '') && headerParamsCount === 0) && bodyParamsCount === 0 && footerParamsCount === 0"
                              class="bg-red-100 border-l-4 rounded border-red-500 text-red-800 px-2 py-3 dark:bg-gray-800 dark:border-red-800 dark:text-red-300"
                              role="alert">
                              <div class="flex justify-start items-center gap-2">
                                <p class="font-base text-sm">
                                  <?php echo e(t('variable_not_available_for_this_template')); ?>

                                </p>
                              </div>
                            </div>

                            
                            <div x-show="inputType !== 'TEXT' || headerParamsCount > 0">
                              <div class="flex items-center justify-start">
                                <label for="dynamic_input" class="block font-medium text-slate-700 dark:text-slate-200">
                                  <template x-if="inputType == 'TEXT' && headerParamsCount > 0">
                                    <span class="text-lg font-semibold"><?php echo e(t('header')); ?></span>
                                  </template>
                                  <template x-if="inputType == 'IMAGE'">
                                    <span class="text-lg font-semibold"><?php echo e(t('image')); ?></span>
                                  </template>
                                  <template x-if="inputType == 'DOCUMENT'">
                                    <span class="text-lg font-semibold"><?php echo e(t('document')); ?></span>
                                  </template>
                                  <template x-if="inputType == 'VIDEO'">
                                    <span class="text-lg font-semibold"><?php echo e(t('video')); ?></span>
                                  </template>
                                </label>
                              </div>

                              <div>
                                <!-- Standard Input with Tailwind CSS -->
                                <template x-if="inputType == 'TEXT'">
                                  <template x-for="(value, index) in headerParamsCount" :key="index">
                                    <div class="mt-2">
                                      <div class="flex justify-start gap-1">
                                        <span class="text-red-500">*</span>
                                        <label :for="'header_name_' + index"
                                          class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                          <?php echo e(t('variable')); ?> <span x-text="index + 1"></span>
                                        </label>
                                      </div>
                                      <input x-bind:type="inputType" :id="'header_name_' + index"
                                        x-model="headerInputs[index]" x-init="initTribute()"
                                        class="mentionable block mt-1 w-full border-slate-300 rounded-md shadow-sm text-slate-900 sm:text-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 dark:border-slate-500 dark:bg-slate-800 dark:placeholder-slate-500 dark:text-slate-200 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:placeholder-slate-600"
                                        autocomplete="off" />
                                      <p x-show="headerInputErrors[index]" x-text="headerInputErrors[index]"
                                        class="text-red-500 text-sm mt-1"></p>
                                    </div>
                                  </template>
                                </template>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('headerInputs.*')): ?>
                                <?php if (isset($component)) { $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dynamic-alert','data' => ['type' => 'danger','message' => $errors->first('headerInputs.*'),'class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('headerInputs.*')),'class' => 'mt-4']); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $attributes = $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $component = $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- File upload sections -->
                                <!-- For DOCUMENT input type (file upload) -->
                                <template x-if="inputType == 'DOCUMENT'">
                                  <div>
                                    <label for="document_upload"
                                      class="block text-sm font-medium text-gray-800 dark:text-gray-300">
                                      <?php echo e(t('select_document')); ?>

                                      <span x-text="metaExtensions.document.extension"></span>
                                    </label>

                                    <div
                                      class="relative mt-1 p-6 border-2 border-dashed rounded-lg cursor-pointer hover:border-blue-500 transition duration-300"
                                      x-on:click="$refs.documentUpload.click()">
                                      <div class="text-center">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-photo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-12 w-12 text-gray-400 mx-auto']); ?>
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
                                        <p class="mt-2 text-sm text-gray-600">
                                          <?php echo e(t('select_or_browse_to')); ?>

                                          <span class="text-blue-600 underline"><?php echo e(t('document')); ?></span>
                                        </p>
                                      </div>
                                      <input type="file" x-ref="documentUpload" id="document_upload"
                                        x-bind:accept="inputAccept" wire:model="file"
                                        x-on:change="handleFilePreview($event)" class="hidden" />
                                    </div>
                                    <template x-if="fileError">
                                      <p class="text-red-500 text-sm mt-2" x-text="fileError"></p>
                                    </template>
                                  </div>
                                </template>

                                <!-- For IMAGE input type (image file upload) -->
                                <template x-if="inputType === 'IMAGE'">
                                  <div>
                                    <label for="image_upload"
                                      class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                      <?php echo e(t('select_image')); ?>

                                      <span x-text="metaExtensions.image.extension"></span>
                                    </label>
                                    <div
                                      class="relative mt-1 p-6 border-2 border-dashed rounded-lg cursor-pointer hover:border-blue-500 transition duration-300"
                                      x-on:click="$refs.imageUpload.click()">
                                      <div class="text-center">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-photo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-12 w-12 text-gray-400 mx-auto']); ?>
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
                                        <p class="mt-2 text-sm text-gray-600">
                                          <?php echo e(t('select_or_browse_to')); ?>

                                          <span class="text-blue-600 underline"><?php echo e(t('image')); ?></span>
                                        </p>
                                      </div>
                                      <input type="file" id="image_upload" x-ref="imageUpload"
                                        x-bind:accept="inputAccept" wire:model="file"
                                        x-on:change="handleFilePreview($event)" class="hidden"
                                        x-on:livewire-upload-start="uploadStarted()"
                                        x-on:livewire-upload-finish="uploadFinished()"
                                        x-on:livewire-upload-error="isUploading = false"
                                        x-on:livewire-upload-progress="updateProgress($event.detail.progress)" />
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('file')): ?>
                                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','for' => 'file']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','for' => 'file']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                                    <?php else: ?>
                                    <template x-if="fileError">
                                      <p class="text-red-500 text-sm mt-2" x-text="fileError">
                                      </p>
                                    </template>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                  </div>
                                </template>

                                <!-- For VIDEO input type (video file upload) -->
                                <template x-if="inputType == 'VIDEO'">
                                  <div>
                                    <label for="video_upload"
                                      class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                      <?php echo e(t('select_video')); ?>

                                    </label>
                                    <span x-text="metaExtensions.video.extension"></span>
                                    <div
                                      class="relative mt-1 p-6 border-2 border-dashed rounded-lg cursor-pointer hover:border-blue-500 transition duration-300"
                                      x-on:click="$refs.videoUpload.click()">
                                      <div class="text-center">
                                        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-s-photo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-12 w-12 text-gray-400 mx-auto']); ?>
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
                                        <p class="mt-2 text-sm text-gray-600">
                                          <?php echo e(t('select_or_browse_to')); ?>

                                          <span class="text-blue-600 underline"><?php echo e(t('video')); ?></span>
                                        </p>
                                      </div>
                                      <input type="file" id="video_upload" x-ref="videoUpload"
                                        x-bind:accept="inputAccept" wire:model.defer="file"
                                        x-on:change="handleFilePreview($event)" class="hidden" />
                                    </div>
                                    <template x-if="fileError">
                                      <p class="text-red-500 text-sm mt-2" x-text="fileError"></p>
                                    </template>
                                  </div>
                                </template>
                                <div x-show="isUploading" class="relative mt-2">
                                  <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                      :style="'width: ' + progress + '%'"></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            
                            <div x-show="bodyParamsCount > 0">
                              <div class="flex items-center justify-start mt-4">
                                <label for="dynamic_input" class="block font-medium text-slate-700 dark:text-slate-200">
                                  <span class="text-lg font-semibold"><?php echo e(t('body')); ?></span>
                                </label>
                              </div>

                              <div>
                                <template x-for="(value, index) in bodyParamsCount" :key="index">
                                  <div class="mt-2">
                                    <div class="flex justify-start gap-1">
                                      <span class="text-red-500">*</span>
                                      <label :for="'body_name_' + index"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e(t('variable')); ?> <span x-text="index + 1"></span>
                                      </label>
                                    </div>
                                    <input type="text" :id="'body_name_' + index" x-model="bodyInputs[index]"
                                      x-init='initTribute()'
                                      class="mentionable block mt-1 w-full border-slate-300 rounded-md shadow-sm text-slate-900 sm:text-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 dark:border-slate-500 dark:bg-slate-800 dark:placeholder-slate-500 dark:text-slate-200 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:placeholder-slate-600"
                                      autocomplete="off" />
                                    <p x-show="bodyInputErrors[index]" x-text="bodyInputErrors[index]"
                                      class="text-red-500 text-sm mt-1"></p>
                                  </div>
                                </template>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('bodyInputs.*')): ?>
                                <?php if (isset($component)) { $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dynamic-alert','data' => ['type' => 'danger','message' => $errors->first('bodyInputs.*'),'class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('bodyInputs.*')),'class' => 'mt-4']); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $attributes = $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $component = $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                              </div>
                            </div>

                            
                            <div x-show="footerParamsCount > 0">
                              <div
                                class="text-gray-600 dark:text-gray-400 border-b mt-6 mb-4 border-gray-300 dark:border-gray-600">
                              </div>

                              <div class="flex items-center justify-start">
                                <label for="dynamic_input" class="block font-medium text-slate-700 dark:text-slate-200">
                                  <span class="text-lg font-semibold"><?php echo e(t('footer')); ?></span>
                                </label>
                              </div>

                              <div>
                                <template x-for="(value, index) in footerInputs" :key="index">
                                  <div class="mt-2">
                                    <div class="flex justify-start gap-1">
                                      <span class="text-red-500">*</span>
                                      <label :for="'footer_name_' + index"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        <?php echo e(t('variable')); ?> <span x-text="index"></span>
                                      </label>
                                    </div>
                                    <input type="text" :id="'footer_name_' + index" x-model="footerInputs[index]"
                                      class="mentionable block mt-1 w-full border-slate-300 rounded-md shadow-sm text-slate-900 sm:text-sm focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 dark:border-slate-500 dark:bg-slate-800 dark:placeholder-slate-500 dark:text-slate-200 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:placeholder-slate-600"
                                      autocomplete="off" />
                                    <p x-show="footerInputErrors[index]" x-text="footerInputErrors[index]"
                                      class="text-red-500 text-sm mt-1"></p>
                                  </div>
                                </template>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('footerInputs.*')): ?>
                                <?php if (isset($component)) { $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dynamic-alert','data' => ['type' => 'danger','message' => $errors->first('footerInputs.*'),'class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'danger','message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('footerInputs.*')),'class' => 'mt-4']); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $attributes = $__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__attributesOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822)): ?>
<?php $component = $__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822; ?>
<?php unset($__componentOriginal58f1ae2fa6fc61c6beeebb5be974a822); ?>
<?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    </div>

                    <!-- Right column: Preview -->
                    <div class="h-full">
                      <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['class' => 'rounded-lg h-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'rounded-lg h-full']); ?>
                         <?php $__env->slot('header', null, []); ?> 
                          <h1 class="text-xl font-semibold text-slate-700 dark:text-slate-300">
                            <?php echo e(t('preview')); ?>

                          </h1>
                         <?php $__env->endSlot(); ?>
                         <?php $__env->slot('content', null, []); ?> 
                          <div class="w-full p-6 border border-gray-200 rounded shadow-sm dark:border-gray-700"
                            style="background-image: url('<?php echo e(asset('img/chat/whatsapp_light_bg.png')); ?>');">
                            <!-- File Preview Section -->
                            <div class="mb-1" x-show="previewUrl">
                              <!-- Image Preview -->
                              <a x-show="inputType === 'IMAGE'" :href="previewUrl" class="glightbox"
                                x-effect="if (previewUrl) { setTimeout(() => initGLightbox(), 100); }">
                                <img x-show="inputType === 'IMAGE'" :src="previewUrl"
                                  class="w-full max-h-60 rounded-lg shadow bg-white dark:bg-gray-800" />
                              </a>

                              <!-- Video Preview -->
                              <video x-show="inputType === 'VIDEO'" :src="previewUrl" controls
                                class="w-full max-h-60 rounded-lg shadow bg-white dark:bg-gray-800 glightbox cursor-pointer"></video>

                              <!-- Document Preview -->
                              <div x-show="inputType === 'DOCUMENT'"
                                class="p-4 border border-gray-300 bg-white dark:bg-gray-800 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                  <?php echo e(t('document_uploaded')); ?>

                                  <a :href="previewUrl" target="_blank"
                                    class="text-blue-500 underline break-all inline-block" x-text="previewFileName"></a>
                                </p>
                              </div>
                            </div>

                            <!-- Campaign Text Section -->
                            <div class="p-6 bg-white rounded-lg dark:bg-gray-800 dark:text-white">
                              <p class="mb-3 font-meduim text-gray-800 dark:text-gray-400"
                                x-html="replaceVariables(campaignHeader, headerInputs)">
                              </p>
                              <p class="mb-3 font-normal text-sm text-gray-500 dark:text-gray-400"
                                x-html="replaceVariables(campaignBody, bodyInputs)">
                              </p>
                              <div class="mt-4">
                                <p class="font-normal text-xs text-gray-500 dark:text-gray-400" x-text="campaignFooter">
                                </p>
                              </div>
                            </div>

                            <template x-if="buttons && buttons.length > 0"
                              class="bg-white rounded-lg py-2 dark:bg-gray-800 dark:text-white">
                              <!-- Check if buttons is defined and not empty -->
                              <div class="space-y-1">
                                <!-- Use space-y-2 for vertical spacing between buttons -->
                                <template x-for="(button, index) in buttons" :key="index">
                                  <div
                                    class="w-full px-4 py-2 bg-white text-gray-900 rounded-md dark:bg-gray-700 dark:text-white">
                                    <span x-text="button.text" class="text-sm block text-center"></span>
                                    <!-- Center the text inside the button -->
                                  </div>
                                </template>
                              </div>
                            </template>
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
                    </div>
                  </div>

                  <!-- Buttons at the bottom -->
                  <div x-show="campaignsSelected" x-cloak
                    class="mt-6 py-4 border-t border-black/5 dark:border-white/5 bg-gradient-to-b from-transparent to-gray-50 dark:to-slate-800/50">
                    <div class="flex justify-end gap-4">
                      <button type="button" x-on:click="isOpen = false;resetModal()"
                        class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700/50 rounded-lg shadow-sm ring-1 ring-black/5 dark:ring-white/5 hover:bg-gray-50 dark:hover:bg-slate-700 hover:shadow-md transition-all">
                        <?php echo e(t('close')); ?>

                      </button>
                      <?php if (isset($component)) { $__componentOriginal533f51d0b2818acbd35337da747efa74 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal533f51d0b2818acbd35337da747efa74 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button.loading-button','data' => ['type' => 'button','target' => 'save','xOn:click' => 'handleSave()','xBind:disabled' => 'isUploading','xBind:class' => '{ \'opacity-50 cursor-not-allowed\': isUploading }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button.loading-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','target' => 'save','x-on:click' => 'handleSave()','x-bind:disabled' => 'isUploading','x-bind:class' => '{ \'opacity-50 cursor-not-allowed\': isUploading }']); ?>
                        <?php echo e(t('submit')); ?>

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
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>

</div>
<?php /**PATH /home/u108339042/domains/dash.chatvoo.com/public_html/resources/views/livewire/tenant/contact/contact-list.blade.php ENDPATH**/ ?>