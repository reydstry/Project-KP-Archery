<?php $__env->startSection('title', 'Monthly Member Activity Report'); ?>
<?php $__env->startSection('subtitle', 'Rekap aktivitas member bulanan, kehadiran, dan slot paket'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <?php if (isset($component)) { $__componentOriginalba70b7059b726609ea102a7adde151ac = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalba70b7059b726609ea102a7adde151ac = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-header','data' => ['title' => 'Laporan Aktivitas Member','subtitle' => 'Periode: '.e(\Carbon\Carbon::createFromDate($filters['year'], $filters['month'], 1)->translatedFormat('F Y')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Laporan Aktivitas Member','subtitle' => 'Periode: '.e(\Carbon\Carbon::createFromDate($filters['year'], $filters['month'], 1)->translatedFormat('F Y')).'']); ?>
         <?php $__env->slot('actions', null, ['class' => 'flex items-center gap-2']); ?> 
            <a
                href="<?php echo e(url('/api/admin/reports/export') . '?' . http_build_query(['mode' => 'monthly', 'month' => $filters['month'], 'year' => $filters['year']])); ?>"
                class="inline-flex w-full items-center justify-center px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-xs sm:text-sm font-semibold hover:bg-[#1a307b]/80 transition"
            > 
                Export Excel
            </a>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalba70b7059b726609ea102a7adde151ac)): ?>
<?php $attributes = $__attributesOriginalba70b7059b726609ea102a7adde151ac; ?>
<?php unset($__attributesOriginalba70b7059b726609ea102a7adde151ac); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalba70b7059b726609ea102a7adde151ac)): ?>
<?php $component = $__componentOriginalba70b7059b726609ea102a7adde151ac; ?>
<?php unset($__componentOriginalba70b7059b726609ea102a7adde151ac); ?>
<?php endif; ?>

    <form method="GET" action="<?php echo e(route('admin.reports.monthly')); ?>" class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-6 gap-3">
        <?php if (isset($component)) { $__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-input','data' => ['label' => 'Bulan','name' => 'month','type' => 'number','min' => '1','max' => '12','value' => ''.e($filters['month']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Bulan','name' => 'month','type' => 'number','min' => '1','max' => '12','value' => ''.e($filters['month']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14)): ?>
<?php $attributes = $__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14; ?>
<?php unset($__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14)): ?>
<?php $component = $__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14; ?>
<?php unset($__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-input','data' => ['label' => 'Tahun','name' => 'year','type' => 'number','min' => '2020','max' => '2100','value' => ''.e($filters['year']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tahun','name' => 'year','type' => 'number','min' => '2020','max' => '2100','value' => ''.e($filters['year']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14)): ?>
<?php $attributes = $__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14; ?>
<?php unset($__attributesOriginal93a7e4fbb8709cb7edbcf616ab99cd14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14)): ?>
<?php $component = $__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14; ?>
<?php unset($__componentOriginal93a7e4fbb8709cb7edbcf616ab99cd14); ?>
<?php endif; ?>

        <div x-data="{
            open: false,
            selected: '<?php echo e($filters['package_id'] ?? ''); ?>',
            packages: [
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {id: '<?php echo e($package->id); ?>', name: '<?php echo e($package->name); ?>'},
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            get selectedName() {
                if(this.selected === '') return 'Semua Paket';
                const pkg = this.packages.find(p => p.id == this.selected);
                return pkg ? pkg.name : this.selected;
            }
        }" class="relative w-full">

            <label class="block text-sm font-semibold text-slate-700 mb-2">Paket / Class</label>

            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selectedName"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 left-0 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-60 overflow-auto"
            >
                <li @click="selected=''; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Semua Paket</li>
                <template x-for="pkg in packages" :key="pkg.id">
                    <li @click="selected=pkg.id; open=false" x-text="pkg.name" class="px-3 py-2 hover:bg-slate-100 cursor-pointer"></li>
                </template>
            </ul>

            <!-- Hidden input untuk form -->
            <input type="hidden" name="package_id" :value="selected">

        </div>


        <!-- Sort By -->
        <div x-data="{ open: false, selected: '<?php echo e($filters['sort'] ?? 'name'); ?>' }" class="relative w-full">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Sort By</label>

            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selectedLabel(selected)"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 left-0 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-60 overflow-auto"
            >
                <li @click="selected='name'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Nama</li>
                <li @click="selected='package'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Paket</li>
                <li @click="selected='attended_sessions'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Kehadiran</li>
                <li @click="selected='remaining_slots'; open=false" class="px-3 py-2 hover:bg-slate-100 cursor-pointer">Sisa Slot</li>
            </ul>

            <input type="hidden" name="sort" :value="selected">

            <script>
                function selectedLabel(value) {
                    const labels = {
                        name: 'Nama',
                        package: 'Paket',
                        attended_sessions: 'Kehadiran',
                        remaining_slots: 'Sisa Slot'
                    };
                    return labels[value] || value;
                }
            </script>
        </div>

        <div x-data="{ open: false, selected: '<?php echo e($filters['direction'] ?? 'asc'); ?>' }" class="relative w-full">
            <label for="direction" class="block text-sm font-semibold text-slate-700 mb-2">Direction</label>

            <!-- Custom select button -->
            <button
                @click="open = !open"
                type="button"
                class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-800 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30"
            >
                <span x-text="selected.toUpperCase()"></span>
                <svg
                    :class="{ 'rotate-180': open }"
                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown options -->
            <ul
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 w-full bg-white border border-slate-300 rounded-lg shadow-lg z-10 max-h-40 overflow-auto"
            >
                <li
                    @click="selected = 'asc'; open = false"
                    class="px-3 py-2 hover:bg-slate-100 cursor-pointer"
                >
                    ASC
                </li>
                <li
                    @click="selected = 'desc'; open = false"
                    class="px-3 py-2 hover:bg-slate-100 cursor-pointer"
                >
                    DESC
                </li>
            </ul>

            <!-- Hidden input to send selected value in form -->
            <input type="hidden" name="direction" :value="selected">
        </div>


        <div class="flex items-center">
            <button type="submit" class="w-full px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold hover:opacity-95 transition">
                Terapkan Filter
            </button>
        </div>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Members']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Members']); ?>
            <p class="mt-2 text-2xl font-bold text-slate-800"><?php echo e($summary['total_members']); ?></p>
            <p class="text-xs text-slate-500 mt-1">Member aktif</p>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Sessions']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Sessions']); ?>
            <p class="mt-2 text-2xl font-bold text-slate-800"><?php echo e($summary['total_sessions']); ?></p>
            <p class="text-xs text-slate-500 mt-1">Jumlah session pada periode</p>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Members Trained']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Members Trained']); ?>
            <p class="mt-2 text-2xl font-bold text-slate-800"><?php echo e($summary['members_trained']); ?></p>
            <p class="text-xs text-slate-500 mt-1">Member yang sudah latihan</p>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Average Attendance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Average Attendance']); ?>
            <p class="mt-2 text-2xl font-bold text-slate-800"><?php echo e(number_format($summary['average_attendance'], 1)); ?>%</p>
            <p class="text-xs text-slate-500 mt-1">Rata-rata kehadiran member</p>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>

    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headers' => ['No', 'Nama', 'Paket', 'Kehadiran', 'Sisa Slot', 'Slot Terpakai']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['No', 'Nama', 'Paket', 'Kehadiran', 'Sisa Slot', 'Slot Terpakai'])]); ?>
        <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e($row['is_low_attendance'] ? 'bg-white' : ''); ?> text-center">
                <td class="px-4 py-3 text-slate-700"><?php echo e($index + 1); ?></td>

                <td class="px-4 py-3">
                    <p class="text-slate-800"><?php echo e($row['member_name']); ?></p>           
                </td>

                <td class="px-4 py-3">
                    <p class="text-slate-700"><?php echo e($row['package_name']); ?></p>
                </td>

                <td class="px-4 py-3 text-slate-700 "><?php echo e($row['attended_sessions']); ?></td>

                <td class="px-4 py-3 text-slate-700"><?php echo e($row['remaining_slots']); ?> slot</td>

                <td class="px-4 py-3 text-slate-700"><?php echo e($row['used_slots']); ?> slot</td>


            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-slate-500">
                    Data report tidak tersedia untuk filter bulan/tahun yang dipilih.
                </td>
            </tr>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/dashboards/admin/reports/monthly.blade.php ENDPATH**/ ?>