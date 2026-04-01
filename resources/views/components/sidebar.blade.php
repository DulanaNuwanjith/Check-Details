<style>
    [x-cloak] {
        display: none !important;
    }

    .scroll-hidden {
        overflow-y: scroll;
        scrollbar-width: none;
        /* Firefox */
    }

    .scroll-hidden::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Edge */
    }
</style>

<script>
    const savedSidebarState = localStorage.getItem('sidebarCollapsed');
    const initialCollapsed = savedSidebarState ? JSON.parse(savedSidebarState) : false;
    window.__initialCollapsed = initialCollapsed;
    document.documentElement.style.setProperty('--sidebar-width', initialCollapsed ? '5rem' : '16rem');
</script>

<!-- Sidebar Component -->
<aside x-data="{
        collapsed: window.__initialCollapsed,
        initialized: false,
        open: false,
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', JSON.stringify(this.collapsed));
            document.documentElement.style.setProperty('--sidebar-width', this.collapsed ? '5rem' : '16rem');
        }
    }"
    x-init="initialized = true"
    :class="[
        collapsed ? 'md:w-20' : 'md:w-64',
        { '-translate-x-full': !open, 'translate-x-0': open }
    ]"
    style="width: var(--sidebar-width); transition-property: width, transform; transition-duration: 300ms;"
    class="fixed md:relative z-40 inset-y-0 left-0 transform md:translate-x-0 bg-white border-r border-gray-200 shadow-sm flex flex-col h-screen scroll-hidden">

    <!-- Mobile Header -->
    <div class="flex justify-between items-center md:hidden p-4 border-b border-gray-100 bg-white">
        <!-- Optional Mobile Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shrink-0 shadow bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <span class="font-bold text-gray-800">Menu</span>
        </a>
        <button @click="open = !open" class="p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition text-gray-600 border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Toggle Button (Desktop) -->
    <div class="hidden md:flex justify-end p-4">
        <button
            @click="toggle()"
            class="bg-white border border-gray-200 rounded-full w-8 h-8 flex items-center justify-center shadow-sm hover:bg-gray-50 transition text-gray-500 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" :d="collapsed ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Header (Logo) -->
    <div class="hidden md:flex items-center justify-center p-4 border-b border-gray-100 mb-2" x-cloak>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full justify-center text-blue-600" :class="collapsed ? 'px-0' : 'px-2'">
            <!-- App Logo icon -->
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0 shadow-md bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <span x-show="!collapsed" class="font-extrabold text-xl text-gray-800 tracking-tight truncate">CheckDetails</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col flex-1 p-3 space-y-1.5 overflow-y-auto scroll-hidden text-sm font-semibold text-gray-600" x-cloak>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : '' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 shrink-0 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span x-show="!collapsed" class="ml-3 truncate">Dashboard</span>
        </a>

        <!-- Check Details -->
        <a href="{{ route('cheques.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('cheques.*') ? 'bg-blue-50 text-blue-600' : '' }}"
           :title="collapsed ? 'Check Details' : ''">
            <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span x-show="!collapsed" class="ml-3 truncate">Check Details</span>
        </a>

         <!-- Bank Accounts -->
        <a href="#"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-blue-50 hover:text-blue-600"
           :title="collapsed ? 'Bank Accounts' : ''">
            <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
            <span x-show="!collapsed" class="ml-3 truncate">Bank Accounts</span>
        </a>

        <!-- User Management -->
        <a href="#"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-blue-50 hover:text-blue-600"
           :title="collapsed ? 'User Management' : ''">
            <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span x-show="!collapsed" class="ml-3 truncate">Users</span>
        </a>

        <!-- Reports -->
        <a href="#"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-blue-50 hover:text-blue-600"
           :title="collapsed ? 'Reports' : ''">
            <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-blue-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span x-show="!collapsed" class="ml-3 truncate">Reports</span>
        </a>
    </nav>

    <!-- Footer Profile & Logout -->
    <div class="border-t border-gray-100 p-3 bg-gray-50/50" x-cloak>
        <!-- Profile -->
        <a href="{{ route('profile.show') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-white hover:shadow-sm text-gray-700 font-semibold"
           :title="collapsed ? 'Profile' : ''">
            <img class="w-8 h-8 rounded-full shrink-0 object-cover border-2 border-white shadow-sm"
                 src="{{ Auth::user() ? Auth::user()->profile_photo_url : 'https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name ?? 'Profile' }}" />
            <span x-show="!collapsed" class="ml-3 truncate">{{ Auth::user()->name ?? 'Profile' }}</span>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-2 text-sm font-semibold text-gray-600">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group hover:bg-red-50 hover:text-red-600"
                    :title="collapsed ? 'Logout' : ''">
                <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-red-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span x-show="!collapsed" class="ml-3 truncate">Logout</span>
            </button>
        </form>
    </div>
</aside>
