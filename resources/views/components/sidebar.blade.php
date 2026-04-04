<!-- FontAwesome (if not global) and Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<style>
    [x-cloak] { display: none !important; }
    
    .sidebar-label {
        display: var(--sidebar-label-display, block) !important;
    }
    
    .sidebar-item { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .sidebar-active {
        background: #2563eb !important;
        color: white !important;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }
    .sidebar-active i { color: white !important; }
    
    .scroll-hidden {
        overflow-y: auto;
        scrollbar-width: none;
    }
    .scroll-hidden::-webkit-scrollbar { display: none; }
</style>

<script>
    (function() {
        const savedSidebarState = localStorage.getItem('sidebarCollapsed');
        const initialCollapsed = savedSidebarState ? JSON.parse(savedSidebarState) : false;
        window.__initialCollapsed = initialCollapsed;
        document.documentElement.style.setProperty('--sidebar-width', initialCollapsed ? '6rem' : '18rem');
        document.documentElement.style.setProperty('--sidebar-label-display', initialCollapsed ? 'none' : 'block');
    })();
</script>

<aside x-data="{
        collapsed: window.__initialCollapsed,
        initialized: false,
        open: false,
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', JSON.stringify(this.collapsed));
            document.documentElement.style.setProperty('--sidebar-width', this.collapsed ? '6rem' : '18rem');
            document.documentElement.style.setProperty('--sidebar-label-display', this.collapsed ? 'none' : 'block');
        }
    }"
    x-init="initialized = true"
    :class="[
        collapsed ? 'md:w-[6rem]' : 'md:w-[18rem]',
        { '-translate-x-full': !open, 'translate-x-0': open }
    ]"
    style="width: var(--sidebar-width); transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s;"
    class="fixed md:relative z-50 inset-y-0 left-0 bg-white border-r border-slate-100 flex flex-col h-screen font-['Outfit'] antialiased">

    <!-- Brand Header -->
    <div class="px-6 py-8 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-11 h-11 bg-blue-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-blue-200 text-white transform transition hover:rotate-6">
                <i class="fa-solid fa-layer-group text-xl"></i>
            </div>
            <div x-cloak x-show="!collapsed" class="sidebar-label" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <span class="block font-black text-xl text-slate-800 tracking-tight leading-none group">CheckDetails</span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1 block">Enterprise v1.0</span>
            </div>
        </a>
    </div>

    <!-- Toggle Button (Desktop Only) -->
    <div class="hidden md:block absolute -right-4 top-10">
        <button @click="toggle()" class="w-8 h-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center shadow-md hover:bg-slate-50 transition-all text-slate-400 hover:text-blue-600 active:scale-95">
            <i class="fa-solid" :class="collapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
        </button>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-4 space-y-2 overflow-y-auto scroll-hidden pt-4">
        
        <p x-cloak x-show="!collapsed" class="sidebar-label px-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 mb-4">Core Menu</p>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-house-chimney text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">Dashboard Overview</span>
        </a>

        <!-- Check Details -->
        <a href="{{ route('cheques.index') }}"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group {{ request()->routeIs('cheques.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}"
           :title="collapsed ? 'Cheque Management' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-money-check-dollar text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">Cheque Management</span>
        </a>

        <!-- Bank Accounts -->
        <a href="{{ route('bank-accounts.index') }}"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group {{ request()->routeIs('bank-accounts.*') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600' }}"
           :title="collapsed ? 'Bank Accounts' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-building-columns text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">Bank Accounts</span>
        </a>

        <div class="my-8 h-px bg-slate-50 mx-4"></div>
        <p x-cloak x-show="!collapsed" class="sidebar-label px-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 mb-4">Operations</p>

        <!-- User Management -->
        <a href="#"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group text-slate-500 hover:bg-slate-50 hover:text-blue-600"
           :title="collapsed ? 'Users' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-users-gear text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">User Access Control</span>
        </a>

        <!-- Reports -->
        <a href="#"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group text-slate-500 hover:bg-slate-50 hover:text-blue-600"
           :title="collapsed ? 'Reports' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-chart-pie text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">Financial Reports</span>
        </a>

        <!-- Settings -->
        <a href="#"
           class="sidebar-item flex items-center px-4 py-3.5 rounded-2xl font-bold group text-slate-500 hover:bg-slate-50 hover:text-blue-600"
           :title="collapsed ? 'System Settings' : ''">
            <div class="w-8 flex justify-center items-center">
                <i class="fa-solid fa-sliders text-lg transition-transform group-hover:scale-110"></i>
            </div>
            <span x-cloak x-show="!collapsed" class="sidebar-label ml-3 tracking-tight whitespace-nowrap">System Settings</span>
        </a>
    </nav>

    <!-- Footer Profile -->
    <div class="p-3 border-t border-slate-50 bg-slate-50/50" :class="collapsed ? 'px-2' : 'p-4'">
        <div class="flex items-center gap-2" :class="collapsed ? 'justify-center' : ''">
            <a href="{{ route('profile.show') }}" 
               class="bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center group transition hover:shadow-md active:scale-[0.98] transition-all duration-300"
               :class="collapsed ? 'p-2 justify-center w-12 h-12 rounded-xl' : 'flex-1 p-4 rounded-3xl'">
                <div class="relative shrink-0">
                    <img class="rounded-xl object-cover border-2 border-white shadow-sm transition-all duration-300"
                         :class="collapsed ? 'w-8 h-8' : 'w-11 h-11 rounded-2xl'"
                         src="{{ Auth::user() ? Auth::user()->profile_photo_url : 'https://ui-avatars.com/api/?name=User&color=3b82f6&background=eff6ff' }}" alt="Profile" />
                    <div class="absolute -bottom-1 -right-1 bg-emerald-500 border-2 border-white rounded-full transition-all"
                         :class="collapsed ? 'w-3 h-3' : 'w-4 h-4'"></div>
                </div>
                <div x-cloak x-show="!collapsed" x-transition.opacity class="sidebar-label ml-4 truncate">
                    <p class="text-slate-800 font-bold text-sm tracking-tight truncate leading-tight group-hover:text-blue-600 transition">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-0.5 truncate">{{ Auth::user()->role ?? 'Super Admin' }}</p>
                </div>
            </a>
            
            <template x-if="!collapsed">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-11 h-11 rounded-2xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition active:scale-95 shadow-sm bg-white border border-slate-100">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </template>
        </div>
        
        <!-- Collapsed Logout Shortcut -->
        <template x-if="collapsed">
            <form method="POST" action="{{ route('logout') }}" class="mt-3 flex justify-center">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition group bg-white border border-slate-100 shadow-sm" title="Logout">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </template>
    </div>
</aside>


