<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Member Dashboard') - FocusOneX Archery</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --brand-primary: #1a307b;
            --brand-secondary: #d12823;
            --brand-dark: #0b0b0f;
        }
        [x-cloak] { display: none !important; }
        .brand-dark-bg { background-color: var(--brand-dark); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .card-animate { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false, isMobile: window.innerWidth < 1024 }" @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = false">
    <!-- Toast Container -->
    <div x-data="toastManager()" @show-toast.window="show($event.detail)" class="fixed top-4 right-4 z-[100] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition
                 class="min-w-[320px] bg-white rounded-xl shadow-xl border p-4 flex items-start gap-3">
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

    <div x-show="sidebarOpen && isMobile"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40"
         x-cloak></div>

    <div class="flex min-h-screen">
        <aside class="fixed top-0 left-0 h-screen w-64 brand-dark-bg text-white border-r border-slate-800 z-50 transition-transform duration-300"
               :class="(sidebarOpen || !isMobile) ? 'translate-x-0' : '-translate-x-full'">
            <div class="h-full flex flex-col">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <img src="{{ asset('asset/img/logowhite.png') }}" alt="FocusOneX Archery" class="h-8 w-auto">
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-300 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                    <a href="{{ route('member.dashboard') }}" @click="if(isMobile) sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.dashboard') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('member.membership') }}" @click="if(isMobile) sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.membership') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Keanggotaan</span>
                    </a>
                          <a href="{{ route('member.membership') }}" @click="if(isMobile) sidebarOpen = false"
                              class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.membership') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Keanggotaan</span>
                    </a>
                    <a href="{{ route('member.achievements') }}" @click="if(isMobile) sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.achievements') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Prestasi</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 min-h-screen">
            <!-- Desktop Header -->
            <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-200 px-8 py-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-700 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title & Subtitle -->
                    <div>
                        <h2 class="text-3xl font-bold text-slate-800">@yield('title')</h2>
                        <p class="text-slate-500 mt-1">@yield('subtitle')</p>
                    </div>

                    <!-- Right Side: Action Buttons + Profile -->
                    <div class="flex items-center gap-3">
                        <!-- Page Actions (if any) -->
                        @stack('header-actions')

                        <!-- User Profile Desktop -->
                        <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100 hover:from-slate-100 hover:to-slate-200 transition-all shadow-sm border border-slate-200">
                            <div class="text-left">
                                <p class="text-slate-800 font-semibold text-sm leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-slate-500 text-xs">Member</p>
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
                            <a href="{{ route('member.profile') }}" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profil
                            </a>
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
            </div>

            <div class="p-8">
                <div class="max-w-7xl">
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
                const options = {
                    method,
                    headers: this.headers,
                    credentials: 'same-origin'
                };

                if (data && ['POST', 'PUT', 'PATCH'].includes(method)) {
                    options.body = JSON.stringify(data);
                }

                const response = await fetch(this.baseUrl + url, options);

                if (!response.ok) {
                    const error = await response.json().catch(() => ({ message: 'Request failed' }));
                    throw new Error(error.message || `HTTP ${response.status}`);
                }

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
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message, type }
            }));
        };
    </script>

    @stack('scripts')
</body>
</html>
