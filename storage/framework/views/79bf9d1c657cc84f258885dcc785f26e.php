<div class="group relative inline-block min-h-[40px]">
    <button class="dark:text-gray-200 text-primary-600 dark:hover:text-primary-400"
        onclick="Livewire.dispatch('viewContact', { contactId: <?php echo e($id); ?> })"><?php echo e($fullName); ?></button>

    <!-- Action Links -->
    <div
        class="absolute left-0 top-3 mt-2 pt-1 hidden contact-actions space-x-1 text-xs text-gray-600 dark:text-gray-300">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.contact.view')): ?>
        <button onclick="Livewire.dispatch('viewContact', { contactId: <?php echo e($id); ?> })" class="hover:text-info-600"><?php echo e(t('view')); ?></button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.contact.edit')): ?>
        <span>|</span>
        <button onclick="Livewire.dispatch('editContact', { contactId: <?php echo e($id); ?> })" class="hover:text-success-600"><?php echo e(t('edit')); ?></button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(checkPermission('tenant.contact.delete')): ?>
        <span>|</span>
        <button onclick="Livewire.dispatch('confirmDelete', { contactId: <?php echo e($id); ?> })" class="hover:text-danger-600"><?php echo e(t('delete')); ?></button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</div><?php /**PATH /home/u108339042/domains/dash.chatvoo.com/public_html/resources/views/components/contacts/name-with-actions.blade.php ENDPATH**/ ?>