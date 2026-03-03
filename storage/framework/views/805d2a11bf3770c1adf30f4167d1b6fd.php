<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - FocusOneX Archery</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --brand-primary: #1a307b;
            --brand-secondary: #d12823;
            --brand-dark: #111111;
        }

        body { overflow-x: hidden; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .brand-primary-bg { background-color: var(--brand-primary); }
        .brand-secondary-bg { background-color: var(--brand-secondary); }
        .brand-dark-bg { background-color: var(--brand-dark); }
        .brand-primary-text { color: var(--brand-primary); }

        .card-animate { animation: fadeInUp .35s ease-out forwards; opacity: 0; }
        
        .brand-active {
            background: var(--brand-primary);
            color: #fff;
            box-shadow: 0 10px 24px -12px rgba(26, 48, 123, 0.75);
        }
        .brand-btn {
            background: var(--brand-primary);
            color: #fff;
        }
        .brand-btn:hover {
            background: #162a6b;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gradient-to-b from-[#16213a] via-[#0f172a] to-[#1b2659] min-h-screen" x-data="{ sidebarOpen: false, isMobile: window.innerWidth < 1024 }" @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = false">

    <div x-show="sidebarOpen && isMobile"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40"
         x-cloak></div>

    <div x-data="toastManager()" <?php echo $__env->yieldSection(); ?>-toast.window="show($event.detail)" class="fixed top-4 right-4 left-4 sm:left-auto z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition class="w-full sm:w-auto sm:min-w-[320px] bg-white rounded-xl shadow-lg border border-slate-200 p-4 flex items-start gap-3">
                <div class="mt-0.5" :class="{ 'text-emerald-600': toast.type === 'success', 'text-red-600': toast.type === 'error', 'text-slate-600': toast.type === 'info' }">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path x-show="toast.type === 'error'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        <path x-show="toast.type === 'info'" stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800" x-text="toast.message"></p>
                </div>
                <button @click="remove(toast.id)" class="text-slate-400 hover:text-slate-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <div class="min-h-screen flex">
    <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-screen w-64 bg-[#0b0b0f] border-r border-slate-800 z-50 transition-transform duration-300 overflow-y-auto shadow-2xl"
               :class="sidebarOpen || !isMobile ? 'translate-x-0' : '-translate-x-full'"
               x-show="sidebarOpen || !isMobile"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            
            <!-- Logo -->
            <div class="p-5 sm:p-6 border-b border-slate-800 sticky top-0 bg-[#0b0b0f]/95 backdrop-blur-sm z-10">
                <div class="flex items-center justify-between gap-3">
                    <img src="<?php echo e(asset('asset/img/logowhite.png')); ?>" alt="FocusOneX Archery" class="h-9 sm:h-10 w-auto">
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <?php
                $sidebarMenu ??= [
                    [
                        'title' => 'Dashboard',
                        'items' => [
                            [
                                'label' => 'Dashboard',
                                'route' => 'dashboard',
                                'patterns' => ['dashboard'],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Attendance Management',
                        'items' => [
                            [
                                'label' => 'Attendance Management',
                                'route' => 'coach.attendance.index',
                                'patterns' => ['coach.attendance.index'],
                            ],
                        ],
                    ],
                ];
            ?>

            <?php if (isset($component)) { $__componentOriginal9b251931889f8782a8cadc1a1a1fd5c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b251931889f8782a8cadc1a1a1fd5c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.global.sidebar-nav','data' => ['menuGroups' => $sidebarMenu]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('global.sidebar-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['menu-groups' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sidebarMenu)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b251931889f8782a8cadc1a1a1fd5c8)): ?>
<?php $attributes = $__attributesOriginal9b251931889f8782a8cadc1a1a1fd5c8; ?>
<?php unset($__attributesOriginal9b251931889f8782a8cadc1a1a1fd5c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b251931889f8782a8cadc1a1a1fd5c8)): ?>
<?php $component = $__componentOriginal9b251931889f8782a8cadc1a1a1fd5c8; ?>
<?php unset($__componentOriginal9b251931889f8782a8cadc1a1a1fd5c8); ?>
<?php endif; ?>
        </aside>

        <main class="flex-1 lg:ml-64 min-h-screen">
        <!-- Desktop Header -->
            <div class="hidden lg:block sticky top-0 z-30 bg-[#0b0b0f] px-8 py-[15px] border-b border-slate-800 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-white"><?php echo $__env->yieldContent('title'); ?></h2>
                        <p class="text-slate-500 mt-0.5 text-xs sm:text-sm"><?php echo $__env->yieldContent('subtitle'); ?></p>
                    </div>
                    <!-- User Profile Desktop -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all shadow-sm border border-slate-200">
                            <div class="text-left">
                                <p class="text-slate-800 font-semibold text-sm leading-tight"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-slate-500 text-xs">Coach</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen" 
                             @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 py-2"
                             x-cloak>
                             <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs text-slate-500 mt-0.5"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                            <a href="<?php echo e(route('coach.change-password')); ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Change Password</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-[#d12823] hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Header -->
            <div class="lg:hidden sticky top-0 z-30 bg-white border-b border-slate-200 px-4 py-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-600 hover:text-slate-900 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Mobile Profile -->
                    <div x-data="{ mobileProfileOpen: false }" class="relative">
                        <button @click="mobileProfileOpen = !mobileProfileOpen" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 transition-all shadow-sm border border-slate-200">
                            <div class="text-left">
                                <p class="text-slate-800 font-semibold text-xs leading-tight"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-slate-500 text-[10px]">Coach</p>
                            </div>
                            <svg class="w-3 h-3 text-slate-400 transition-transform" :class="mobileProfileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Mobile Dropdown -->
                        <div x-show="mobileProfileOpen" 
                             @click.away="mobileProfileOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 py-2"
                             x-cloak>
                             <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs text-slate-500 mt-0.5"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                            <a href="<?php echo e(route('coach.change-password')); ?>" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Change Password</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-[#d12823] hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
                <div class="max-w-7xl mx-auto space-y-4">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toastManager() {
            return {
                toasts: [],
                nextId: 1,
                show(detail) {
                    const id = this.nextId++;
                    const toast = {
                        id,
                        message: detail.message || 'Notification',
                        type: detail.type || 'info',
                        visible: true
                    };
                    this.toasts.push(toast);
                    setTimeout(() => this.remove(id), 3500);
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => this.toasts.splice(index, 1), 250);
                    }
                }
            }
        }

        const API = {
            baseUrl: '/api',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },

            async request(method, url, data = null) {
                const isFormData = data instanceof FormData;
                const headers = { ...this.headers };

                if (isFormData) {
                    delete headers['Content-Type'];
                }

                const options = { method, headers, credentials: 'same-origin' };

                if (data && ['POST', 'PUT', 'PATCH'].includes(method)) {
                    options.body = isFormData ? data : JSON.stringify(data);
                }

                const response = await fetch(this.baseUrl + url, options);
                const contentType = response.headers.get('content-type') || '';
                const isJson = contentType.includes('application/json');

                if (!response.ok) {
                    let errorMessage = `HTTP ${response.status}`;

                    if (response.status === 419) {
                        throw new Error('CSRF token mismatch. Refresh halaman lalu coba lagi.');
                    }

                    if (isJson) {
                        const error = await response.json().catch(() => null);
                        if (error?.errors && typeof error.errors === 'object') {
                            const firstError = Object.values(error.errors)?.[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : (error.message || errorMessage);
                        } else {
                            errorMessage = error?.message || errorMessage;
                        }
                    } else {
                        errorMessage = `Server error (${response.status})`;
                    }

                    throw new Error(errorMessage);
                }

                if (!isJson) return {};
                return await response.json();
            },

            get(url) { return this.request('GET', url); },
            post(url, data) { return this.request('POST', url, data); },
            put(url, data) { return this.request('PUT', url, data); },
            patch(url, data) { return this.request('PATCH', url, data); },
            delete(url) { return this.request('DELETE', url); }
        };

        window.API = API;

        window.showToast = (message, type = 'info') => {
            window.dispatchEvent(new CustomEvent('show-toast', { detail: { message, type } }));
        };

        document.addEventListener('DOMContentLoaded', () => {
            <?php if(session('success')): ?>
                window.showToast(<?php echo json_encode(session('success'), 15, 512) ?>, 'success');
            <?php endif; ?>
            <?php if(session('error')): ?>
                window.showToast(<?php echo json_encode(session('error'), 15, 512) ?>, 'error');
            <?php endif; ?>
            <?php if(session('warning')): ?>
                window.showToast(<?php echo json_encode(session('warning'), 15, 512) ?>, 'info');
            <?php endif; ?>
            <?php if(session('status')): ?>
                window.showToast(<?php echo json_encode(session('status'), 15, 512) ?>, 'success');
            <?php endif; ?>
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/layouts/coach.blade.php ENDPATH**/ ?>