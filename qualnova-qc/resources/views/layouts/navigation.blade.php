<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'bg-white/70 backdrop-blur-xl border-b-transparent shadow-none': !scrolled, 'bg-white/90 backdrop-blur-2xl border-b border-gray-100 shadow-premium': scrolled }"
     class="sticky top-0 z-50 transition-all duration-500">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">

            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200 group-hover:rotate-12 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-tighter italic uppercase text-gray-900 group-hover:text-indigo-600 transition-colors">
                        Qual <span class="text-indigo-600 group-hover:text-gray-900">Nova</span>
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center bg-gray-100/80 p-1.5 rounded-2xl border border-gray-200/50 shadow-inner">
                
                @php
                    // Helper untuk menentukan class active/inactive secara bersih
                    $activeClass = "bg-white shadow-sm text-indigo-600 ring-1 ring-black/5";
                    $inactiveClass = "text-gray-500 hover:text-indigo-600 hover:bg-white/40";
                    $baseClass = "px-6 py-2.5 rounded-[0.9rem] text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 flex items-center gap-2";
                @endphp

                <a href="{{ route('dashboard') }}" 
                   class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8" /></svg>
                    Dashboard
                </a>

                @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                <a href="{{ route('users.index') }}" 
                   class="{{ $baseClass }} {{ request()->routeIs('users.*') ? $activeClass : $inactiveClass }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    User
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                <a href="{{ route('jenis_cacat.index') }}" 
                   class="{{ $baseClass }} {{ request()->routeIs('jenis_cacat.*') ? $activeClass : $inactiveClass }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5-2a9 9 0 11-9-9 9 9 0 019 9z" /></svg>
                    Kategori
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc', 'operator_produksi']))
                <a href="{{ route('data-cacat.index') }}" 
                   class="{{ $baseClass }} {{ request()->routeIs('data-cacat.*') ? $activeClass : $inactiveClass }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span>
                        {{ Auth::user()->role === 'petugas_qc' ? 'Verifikasi' : (Auth::user()->role === 'operator_produksi' ? 'Input QC' : 'Database QC') }}
                    </span>
                </a>
                @endif
            </div>

            <div class="hidden md:flex items-center gap-4">
                <div class="h-8 w-[1px] bg-gray-200/50 mx-2"></div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 p-1.5 pr-4 bg-white/50 hover:bg-white rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-50/50 transition-all duration-300">
                            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-xs uppercase italic shadow-lg shadow-indigo-100">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] leading-none mb-1">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                                <p class="text-xs font-black text-gray-900 leading-none italic uppercase">{{ Auth::user()->name }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-50 mb-1">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Akun Terhubung</p>
                            <p class="text-xs font-bold text-gray-700 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                      

                        <div class="border-t border-gray-50 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 font-black uppercase text-[10px] tracking-widest text-rose-600 hover:bg-rose-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Keluar Sistem
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex md:hidden">
                <button @click="open = ! open" class="p-2.5 rounded-xl bg-gray-100 text-gray-600 hover:text-indigo-600 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-2xl">
        
        <div class="p-4 space-y-2">
            @php
                $mobileBase = "block px-5 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] transition-all";
                $mobileActive = "bg-indigo-600 text-white shadow-lg shadow-indigo-100";
                $mobileInactive = "text-gray-500 hover:bg-gray-50";
            @endphp

            <a href="{{ route('dashboard') }}" class="{{ $mobileBase }} {{ request()->routeIs('dashboard') ? $mobileActive : $mobileInactive }}">Dashboard</a>

            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                <a href="{{ route('users.index') }}" class="{{ $mobileBase }} {{ request()->routeIs('users.*') ? $mobileActive : $mobileInactive }}">Manajemen User</a>
            @endif

            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                <a href="{{ route('jenis_cacat.index') }}" class="{{ $mobileBase }} {{ request()->routeIs('jenis_cacat.*') ? $mobileActive : $mobileInactive }}">Jenis Cacat</a>
            @endif

            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc', 'operator_produksi']))
                <a href="{{ route('data-cacat.index') }}" class="{{ $mobileBase }} {{ request()->routeIs('data-cacat.*') ? $mobileActive : $mobileInactive }}">Data Quality Control</a>
            @endif
        </div>

        <div class="p-6 border-t border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black italic uppercase">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-black text-gray-900 uppercase italic leading-none mb-1">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ Auth::user()->email }}</div>
                </div>
            </div>
            
           
        </div>
    </div>
</nav>