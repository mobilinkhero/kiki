<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
  <link rel="stylesheet" href="<?php echo e(asset('location/leaflet.css')); ?>" crossorigin="" />
      <link rel="stylesheet" href="<?php echo e(asset('location/fullscreen.css')); ?>" />
   <?php $__env->slot('title', null, []); ?> 
    <?php echo e(t('create_flow')); ?>

   <?php $__env->endSlot(); ?>
  <div class="mx-auto h-full">
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
      <div class="w-full overflow-x-auto bg-white dark:bg-gray-800">
        <div id="bot-flow-builder" data-flow-id="<?php echo e($flow->id); ?>" class="w-full">
          <bot-flow-builder></bot-flow-builder>
        </div>
      </div>
    </div>
  </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<script src="<?php echo e(asset('location/leaflet.js')); ?>" crossorigin=""></script>

    <script src="<?php echo e(asset('location/fullscreen.js')); ?>"></script>
<script>
  // Personal Assistant data for AI node
  window.personalAssistantData = <?php echo json_encode(apply_filters('botflow.personal_assistant', $flow), 512) ?>;

  // AI Assistant module enabled flag
  window.isAiAssistantModuleEnabled = <?php echo json_encode($isAiAssistantModuleEnabled, 15, 512) ?>;

  // List of available personal assistants for selection in AI Assistant node
  window.personalAssistantsList = <?php echo json_encode($personalAssistantsList ?? [], 15, 512) ?>;

  // Debug: Log personal assistants list
  console.log('=== PERSONAL ASSISTANTS DEBUG ===');
  console.log('personalAssistantsList:', window.personalAssistantsList);
  console.log('Count:', window.personalAssistantsList?.length || 0);
  console.log('Full data:', JSON.stringify(window.personalAssistantsList, null, 2));

  // Meta allowed extensions
  window.metaAllowedExtensions = <?php echo json_encode(get_meta_allowed_extension(), 15, 512) ?>;
</script>
<?php /**PATH /home/u108339042/domains/dash.chatvoo.com/public_html/resources/views/tenant/bot-flows/edit.blade.php ENDPATH**/ ?>