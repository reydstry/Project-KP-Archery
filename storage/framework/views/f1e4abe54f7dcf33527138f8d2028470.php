<!-- Berita Section -->
<div class="bg-white py-12 sm:py-14 md:py-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Berita Header -->
        <div class="mb-8 sm:mb-10 md:mb-12 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 sm:mb-3"><?php echo e(__('gallery.news_title')); ?></h2>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 px-4"><?php echo e(__('gallery.news_subtitle')); ?></p>
        </div>

        <!-- Berita Content -->
        <div id="content-berita">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                
                <?php if (isset($component)) { $__componentOriginal1d1b664e9e4c06c26083391ace971912 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d1b664e9e4c06c26083391ace971912 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.news-card','data' => ['id' => 1,'image' => asset('asset/img/latarbelakanglogin.jpeg'),'title' => 'Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026','date' => '20 Januari 2026','excerpt' => 'Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan. Prestasi membanggakan ini...','alt' => 'Juara 1 Kompetisi Regional']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.news-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 1,'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/latarbelakanglogin.jpeg')),'title' => 'Tim FocusOneX Raih Juara 1 Kompetisi Regional 2026','date' => '20 Januari 2026','excerpt' => 'Tim panahan FocusOneX berhasil meraih juara pertama dalam kompetisi regional yang diselenggarakan di Balikpapan. Prestasi membanggakan ini...','alt' => 'Juara 1 Kompetisi Regional']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d1b664e9e4c06c26083391ace971912)): ?>
<?php $attributes = $__attributesOriginal1d1b664e9e4c06c26083391ace971912; ?>
<?php unset($__attributesOriginal1d1b664e9e4c06c26083391ace971912); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d1b664e9e4c06c26083391ace971912)): ?>
<?php $component = $__componentOriginal1d1b664e9e4c06c26083391ace971912; ?>
<?php unset($__componentOriginal1d1b664e9e4c06c26083391ace971912); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal1d1b664e9e4c06c26083391ace971912 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d1b664e9e4c06c26083391ace971912 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.galeri.news-card','data' => ['id' => 2,'image' => asset('asset/img/latarbelakanglogin.jpeg'),'title' => 'Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026','date' => '15 Januari 2026','excerpt' => 'Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini...','alt' => 'Atlet Terbaik']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('galeri.news-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 2,'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(asset('asset/img/latarbelakanglogin.jpeg')),'title' => 'Atlet FocusOneX Terpilih sebagai Atlet Terbaik 2026','date' => '15 Januari 2026','excerpt' => 'Salah satu atlet FocusOneX berhasil mendapat penghargaan sebagai atlet terbaik dalam ajang kompetisi nasional tahun ini...','alt' => 'Atlet Terbaik']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d1b664e9e4c06c26083391ace971912)): ?>
<?php $attributes = $__attributesOriginal1d1b664e9e4c06c26083391ace971912; ?>
<?php unset($__attributesOriginal1d1b664e9e4c06c26083391ace971912); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d1b664e9e4c06c26083391ace971912)): ?>
<?php $component = $__componentOriginal1d1b664e9e4c06c26083391ace971912; ?>
<?php unset($__componentOriginal1d1b664e9e4c06c26083391ace971912); ?>
<?php endif; ?>

            </div>
        </div>

    </div>
</div>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/galeri/news-section.blade.php ENDPATH**/ ?>