<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - FocusOneX Archery</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --brand-primary: #1a307b;
            --brand-secondary: #d12823;
            --brand-black: #0b0b0f;
        }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .card-animate { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Mobile overflow fix */
        @media (max-width: 1023px) {
            body { overflow-x: hidden; }
            .main-content { max-width: 100vw; overflow-x: auto; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false, isMobile: window.innerWidth < 1024 }" @resize.window="isMobile = window.innerWidth < 1024">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen && isMobile" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         x-cloak></div>

    <!-- Toast Container -->
    <div x-data="toastManager()" @show-toast.window="show($event.detail)" class="fixed top-4 right-4 left-4 sm:left-auto z-100 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition
                 class="w-full sm:w-auto sm:min-w-[320px] bg-white rounded-xl shadow-2xl border border-slate-200 p-4 flex items-start gap-3">
                <div class="mt-0.5" :class="{
                    'text-green-500': toast.type === 'success',
                    'text-red-500': toast.type === 'error',
                    'text-blue-500': toast.type === 'info'
                }">
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

    <div class="flex min-h-screen">
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
                    <img src="{{ asset('asset/img/logowhite.png') }}" alt="FocusOneX Archery" class="h-9 sm:h-10 w-auto">
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-3 sm:p-4 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('admin.members') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.members') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="truncate">Members</span>
                </a>
                <a href="{{ route('admin.coaches') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.coaches') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="truncate">Coaches</span>
                </a>
                <a href="{{ route('admin.packages') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.packages') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="truncate">Packages</span>
                </a>
                <a href="{{ route('admin.member-packages') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.member-packages') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span class="truncate">Member Packages</span>
                </a>

                <a href="{{ route('admin.sessions.index') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.sessions.*') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                    <span class="truncate">Training Session</span>
                </a>
                <a href="{{ route('admin.bookings.create') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.bookings.*') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                    <span class="truncate">Booking Sesi</span>
                </a>
                <a href="{{ route('admin.news') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.news') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span class="truncate">News</span>
                </a>
                <a href="{{ route('admin.achievements') }}" 
                   @click="if(isMobile) sidebarOpen = false"
                   class="flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-xl text-[13px] lg:text-sm font-semibold transition-all {{ request()->routeIs('admin.achievements') ? 'brand-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <span class="truncate">Achievements</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 min-h-screen">
            <!-- Desktop Header -->
            <div class="hidden lg:block sticky top-0 z-30 bg-white border-b border-slate-200 px-8 py-4 shadow-sm">
                <div class="flex items-center justify-end">
                    <!-- User Profile Desktop -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all shadow-sm border border-slate-200">
                            <div class="text-left">
                                <p class="text-slate-800 font-semibold text-sm leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-slate-500 text-xs">Administrator</p>
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
                                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
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
                                <p class="text-slate-800 font-semibold text-xs leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-slate-500 text-[10px]">Administrator</p>
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
                                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-3 sm:mb-6 bg-white border border-slate-200 rounded-2xl px-5 sm:px-6 py-4 shadow-sm">
                        <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-[#1a307b]">@yield('title')</h2>
                        <p class="text-slate-500 mt-0.5 text-xs sm:text-sm">@yield('subtitle')</p>
                    </div>

                    <!-- Content -->
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toast Manager
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
                    setTimeout(() => this.remove(id), 3000);
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            }
        }

        // API Helper
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

                const options = {
                    method,
                    headers,
                    credentials: 'same-origin'
                };
                
                if (data && ['POST', 'PUT', 'PATCH'].includes(method)) {
                    options.body = isFormData ? data : JSON.stringify(data);
                }
                
                const response = await fetch(this.baseUrl + url, options);
                
                // Check content-type before parsing
                const contentType = response.headers.get('content-type');
                const isJson = contentType && contentType.includes('application/json');
                
                if (!response.ok) {
                    let errorMessage = `HTTP ${response.status}`;

                    if (response.status === 419) {
                        throw new Error('CSRF token mismatch. Refresh halaman lalu coba lagi.');
                    }
                    
                    if (isJson) {
                        try {
                            const error = await response.json();
                            if (error.errors && typeof error.errors === 'object') {
                                const firstError = Object.values(error.errors)?.[0];
                                errorMessage = Array.isArray(firstError) ? firstError[0] : (error.message || errorMessage);
                            } else {
                                errorMessage = error.message || errorMessage;
                            }
                        } catch (e) {
                            console.error('Failed to parse error response:', e);
                        }
                    } else {
                        const text = await response.text();
                        console.error('Non-JSON error response:', text.substring(0, 200));
                        errorMessage = `Server error (${response.status})`;
                    }
                    
                    throw new Error(errorMessage);
                }
                
                if (!isJson) {
                    return {};
                }
                
                return await response.json();
            },
            
            get(url) { return this.request('GET', url); },
            post(url, data) { return this.request('POST', url, data); },
            put(url, data) { return this.request('PUT', url, data); },
            patch(url, data) { return this.request('PATCH', url, data); },
            delete(url) { return this.request('DELETE', url); }
        };

        // Make API globally available
        window.API = API;

        // Helper to show toast
        window.showToast = (message, type = 'info') => {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message, type }
            }));
        };

        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.showToast(@json(session('success')), 'success');
            @endif
            @if(session('error'))
                window.showToast(@json(session('error')), 'error');
            @endif
            @if(session('warning'))
                window.showToast(@json(session('warning')), 'info');
            @endif
            @if(session('status'))
                window.showToast(@json(session('status')), 'success');
            @endif
        });
    </script>

    <!-- Admin Modal System -->
    <script>
        // Custom Modal System for Admin Panel
        (function() {
            'use strict';

            // Create modal container on DOM load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initModal);
            } else {
                initModal();
            }

            function initModal() {
                // Add modal styles
                const style = document.createElement('style');
                style.textContent = `
                    .admin-modal-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-color: rgba(0, 0, 0, 0.5);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        padding: 1rem;
                    }
                    .admin-modal {
                        background: white;
                        border-radius: 0.5rem;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                        max-width: 28rem;
                        width: 100%;
                        overflow: hidden;
                    }
                    .admin-modal-header {
                        padding: 1rem 1.5rem;
                        color: white;
                        font-weight: 600;
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }
                    .admin-modal-body {
                        padding: 1.5rem;
                        color: #374151;
                        white-space: pre-line;
                    }
                    .admin-modal-footer {
                        padding: 1rem 1.5rem;
                        background-color: #f9fafb;
                        display: flex;
                        gap: 0.75rem;
                        justify-content: flex-end;
                    }
                    .admin-modal-btn {
                        padding: 0.5rem 1rem;
                        border-radius: 0.375rem;
                        font-weight: 500;
                        cursor: pointer;
                        border: none;
                        transition: all 0.2s;
                    }
                    .admin-modal-btn-primary {
                        background-color: #3b82f6;
                        color: white;
                    }
                    .admin-modal-btn-primary:hover {
                        background-color: #2563eb;
                    }
                    .admin-modal-btn-danger {
                        background-color: #ef4444;
                        color: white;
                    }
                    .admin-modal-btn-danger:hover {
                        background-color: #dc2626;
                    }
                    .admin-modal-btn-warning {
                        background-color: #f59e0b;
                        color: white;
                    }
                    .admin-modal-btn-warning:hover {
                        background-color: #d97706;
                    }
                    .admin-modal-btn-secondary {
                        background-color: #e5e7eb;
                        color: #374151;
                    }
                    .admin-modal-btn-secondary:hover {
                        background-color: #d1d5db;
                    }
                    .admin-modal-overlay.fade-in {
                        animation: fadeIn 0.2s ease-out;
                    }
                    .admin-modal.slide-up {
                        animation: slideUp 0.3s ease-out;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    @keyframes slideUp {
                        from {
                            opacity: 0;
                            transform: translateY(1rem);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            // Show confirmation modal
            window.showConfirm = function(title, message, onConfirm, options = {}) {
                const {
                    confirmText = 'Confirm',
                    cancelText = 'Cancel',
                    type = 'info', // info, warning, danger
                    icon = ''
                } = options;

                const overlay = document.createElement('div');
                overlay.className = 'admin-modal-overlay fade-in';

                const headerColors = {
                    info: 'background-color: #3b82f6;',
                    warning: 'background-color: #f59e0b;',
                    danger: 'background-color: #ef4444;'
                };

                const buttonClass = {
                    info: 'admin-modal-btn-primary',
                    warning: 'admin-modal-btn-warning',
                    danger: 'admin-modal-btn-danger'
                };

                overlay.innerHTML = `
                    <div class="admin-modal slide-up">
                        <div class="admin-modal-header" style="${headerColors[type]}">
                            ${icon ? `<span style="font-size: 1.25rem;">${icon}</span>` : ''}
                            <span>${title}</span>
                        </div>
                        <div class="admin-modal-body">${message}</div>
                        <div class="admin-modal-footer">
                            <button class="admin-modal-btn admin-modal-btn-secondary" data-action="cancel">
                                ${cancelText}
                            </button>
                            <button class="admin-modal-btn ${buttonClass[type]}" data-action="confirm">
                                ${confirmText}
                            </button>
                        </div>
                    </div>
                `;

                document.body.appendChild(overlay);
                document.body.style.overflow = 'hidden';

                const modal = overlay.querySelector('.admin-modal');
                
                const closeModal = () => {
                    document.body.style.overflow = '';
                    overlay.remove();
                };

                // Handle confirm
                overlay.querySelector('[data-action="confirm"]').addEventListener('click', () => {
                    closeModal();
                    if (onConfirm) onConfirm();
                });

                // Handle cancel
                overlay.querySelector('[data-action="cancel"]').addEventListener('click', closeModal);

                // ESC key to cancel
                const handleEsc = (e) => {
                    if (e.key === 'Escape') {
                        closeModal();
                        document.removeEventListener('keydown', handleEsc);
                    }
                };
                document.addEventListener('keydown', handleEsc);

                // Prevent click on modal content from closing
                modal.addEventListener('click', (e) => e.stopPropagation());
            };

            // Show alert modal (no cancel button)
            window.showAlert = function(title, message, type = 'info') {
                const overlay = document.createElement('div');
                overlay.className = 'admin-modal-overlay fade-in';

                const headerColors = {
                    success: 'background-color: #10b981;',
                    error: 'background-color: #ef4444;',
                    warning: 'background-color: #f59e0b;',
                    info: 'background-color: #3b82f6;'
                };

                const icons = {
                    success: '✅',
                    error: '❌',
                    warning: '⚠️',
                    info: 'ℹ️'
                };

                overlay.innerHTML = `
                    <div class="admin-modal slide-up">
                        <div class="admin-modal-header" style="${headerColors[type]}">
                            <span style="font-size: 1.25rem;">${icons[type]}</span>
                            <span>${title}</span>
                        </div>
                        <div class="admin-modal-body">${message}</div>
                        <div class="admin-modal-footer">
                            <button class="admin-modal-btn admin-modal-btn-primary" data-action="close">
                                OK
                            </button>
                        </div>
                    </div>
                `;

                document.body.appendChild(overlay);
                document.body.style.overflow = 'hidden';

                const modal = overlay.querySelector('.admin-modal');
                
                const closeModal = () => {
                    document.body.style.overflow = '';
                    overlay.remove();
                };

                overlay.querySelector('[data-action="close"]').addEventListener('click', closeModal);
                
                // ESC key or click outside to close
                const handleEsc = (e) => {
                    if (e.key === 'Escape') {
                        closeModal();
                        document.removeEventListener('keydown', handleEsc);
                    }
                };
                document.addEventListener('keydown', handleEsc);
                overlay.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => e.stopPropagation());
            };

            // Shorthand functions
            window.showSuccess = function(message, title = 'Success') {
                window.showAlert(title, message, 'success');
            };

            window.showError = function(message, title = 'Error') {
                window.showAlert(title, message, 'error');
            };

            window.showWarning = function(message, title = 'Warning') {
                window.showAlert(title, message, 'warning');
            };

            window.showInfo = function(message, title = 'Information') {
                window.showAlert(title, message, 'info');
            };
        })();
    </script>

    @stack('scripts')
</body>
</html>
