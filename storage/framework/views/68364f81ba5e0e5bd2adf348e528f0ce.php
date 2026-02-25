<!-- Header -->
<section class="relative min-h-screen py-32 
    bg-gradient-to-b from-[#16213a] via-[#0f172a] to-[#1b2659] overflow-hidden">
    
    <!-- Background decorative blur -->
    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>


    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
          <?php echo e(__('gallery.header_title')); ?>

        </h1>
        <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
          <?php echo e(__('gallery.header_subtitle')); ?>

        </p>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-8 sm:mb-10">
        <div class="flex flex-wrap justify-center gap-2 sm:gap-4 border-b-2 border-gray-200 px-4">
            <button onclick="switchTab('latihan')" id="tab-latihan" class="tab-button active px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_training')); ?>

            </button>
            <button onclick="switchTab('kompetisi')" id="tab-kompetisi" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_competition')); ?>

            </button>
            <button onclick="switchTab('event')" id="tab-event" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
                <?php echo e(__('gallery.tab_group_selfie')); ?>

            </button>
        </div>
    </div>

    <!-- Tab Content: Latihan -->
<div id="content-latihan" class="tab-content">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        
        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/latihan/Latihan dasar.jpg'),'title' => 'Latihan Rutin Mingguan','alt' => 'Latihan Rutin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/latihan/Latihan dasar.jpg')),'title' => 'Latihan Rutin Mingguan','alt' => 'Latihan Rutin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/latihan/Latihan dasar1.png'),'title' => 'Latihan Rutin','alt' => 'Latihan Rutin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/latihan/Latihan dasar1.png')),'title' => 'Latihan Rutin','alt' => 'Latihan Rutin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/latihan/Latihan dasar2.png'),'title' => 'Latihan Rutin','alt' => 'Latihan Rutin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/latihan/Latihan dasar2.png')),'title' => 'Latihan Rutin','alt' => 'Latihan Rutin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

    </div>
</div>

<!-- Tab Content: Kompetisi -->
<div id="content-kompetisi" class="tab-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        
        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/kompetisi/kompetisi manah1.jpg'),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/kompetisi/kompetisi manah1.jpg')),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/kompetisi/kompetisi manah.png'),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/kompetisi/kompetisi manah.png')),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/kompetisi/penghargaan.jpg'),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/kompetisi/penghargaan.jpg')),'title' => 'Seleksi kejurnas provinsi','alt' => 'Seleksi kejurnas provinsi']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

    </div>
</div>

<!-- Tab Content: -->
<div id="content-event" class="tab-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        
        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/baltim.jpg'),'title' => '','alt' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/baltim.jpg')),'title' => '','alt' => '']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/baltim1.JPG'),'title' => '','alt' => ' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/baltim1.JPG')),'title' => '','alt' => ' ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

         <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/bkpo.png'),'title' => '','alt' => ' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/bkpo.png')),'title' => '','alt' => ' ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>

         <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/kejurnas.JPG'),'title' => '','alt' => ' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/kejurnas.JPG')),'title' => '','alt' => ' ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
         <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/kejurnas1.JPG'),'title' => '','alt' => ' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/kejurnas1.JPG')),'title' => '','alt' => ' ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginald3414607778ba630b571a3ed5dcf2390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3414607778ba630b571a3ed5dcf2390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.card','data' => ['image' => asset('asset/img/galeri/group/17an.png'),'title' => '','alt' => ' ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/galeri/group/17an.png')),'title' => '','alt' => ' ']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $attributes = $__attributesOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__attributesOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3414607778ba630b571a3ed5dcf2390)): ?>
<?php $component = $__componentOriginald3414607778ba630b571a3ed5dcf2390; ?>
<?php unset($__componentOriginald3414607778ba630b571a3ed5dcf2390); ?>
<?php endif; ?>
    </div>
    
</div>

</section><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/galeri/hero-section.blade.php ENDPATH**/ ?>