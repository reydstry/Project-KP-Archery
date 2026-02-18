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
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false, isMobile: window.innerWidth < 1024 }" @resize.window="isMobile = window.innerWidth < 1024; if(!isMobile) sidebarOpen = false">

    <div x-show="sidebarOpen && isMobile"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40"
         x-cloak></div>

    <div x-data="toastManager()" @show-toast.window="show($event.detail)" class="fixed top-4 right-4 left-4 sm:left-auto z-50 space-y-2">
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
                    <a href="{{ route('dashboard') }}" @click="if(isMobile) sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                          <a href="{{ route('coach.sessions.index') }}" @click="if(isMobile) sidebarOpen = false"
                              class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('coach.sessions.*') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Sesi Latihan</span>
                    </a>
                    <a href="{{ route('coach.change-password') }}" @click="if(isMobile) sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('coach.change-password') ? 'bg-[#1a307b] text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Ganti Password</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 lg:ml-64 min-h-screen">
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200">
                <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-700 hover:text-slate-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="hidden lg:block">
                        <h1 class="text-base font-semibold text-slate-900">Coach Panel</h1>
                    </div>

                    <div x-data="{ profileOpen: false }" class="relative ml-auto">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">Coach</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="profileOpen" @click.away="profileOpen = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-lg py-2">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('coach.change-password') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Change Password</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-[#d12823] hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6">
                <div class="max-w-7xl mx-auto space-y-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">@yield('title')</h2>
                        <p class="text-sm text-slate-500 mt-1">@yield('subtitle')</p>
                    </div>

                    @yield('content')
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

    @stack('scripts')
</body>
</html>
