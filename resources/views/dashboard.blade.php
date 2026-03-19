<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Check Details') }} - Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Load Alpine via CDN if not compiled into app.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles -->
        @livewireStyles
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="text-gray-900 bg-gray-50 flex h-screen overflow-hidden antialiased">
        
        <!-- Collapsible Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Top Header Area (Optional minimalist header) -->
            <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 z-10 sticky top-0">
                <div>
                    <h1 class="font-extrabold text-2xl text-gray-800 tracking-tight">Dashboard Overview</h1>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Welcome back, {{ Auth::user()->name ?? 'User' }}. Here's what's happening with your checks today.</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center gap-4">
                    <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold py-2 px-4 rounded-xl transition duration-200 flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Check Entry
                    </button>
                    <!-- Notification Bell -->
                    <button class="relative p-2 text-gray-400 hover:text-gray-600 transition bg-white border border-gray-200 rounded-full shadow-sm hover:shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 block h-2.5 w-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
                <div class="max-w-7xl mx-auto space-y-8">
                    
                    <!-- Analytics Stat Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Stat Card 1 -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 bg-blue-50 w-24 h-24 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">+12%</span>
                            </div>
                            <div>
                                <h3 class="text-3xl font-extrabold text-gray-800">1,284</h3>
                                <p class="text-sm font-semibold text-gray-500 mt-1">Total Checks Processed</p>
                            </div>
                        </div>

                        <!-- Stat Card 2 -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 bg-emerald-50 w-24 h-24 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">+8.4%</span>
                            </div>
                            <div>
                                <h3 class="text-3xl font-extrabold text-gray-800">856</h3>
                                <p class="text-sm font-semibold text-gray-500 mt-1">Cleared Checks</p>
                            </div>
                        </div>

                        <!-- Stat Card 3 -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 bg-amber-50 w-24 h-24 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full">-2.1%</span>
                            </div>
                            <div>
                                <h3 class="text-3xl font-extrabold text-gray-800">342</h3>
                                <p class="text-sm font-semibold text-gray-500 mt-1">Pending Clearance</p>
                            </div>
                        </div>

                        <!-- Stat Card 4 -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 bg-red-50 w-24 h-24 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center shrink-0 shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full">+14.2%</span>
                            </div>
                            <div>
                                <h3 class="text-3xl font-extrabold text-gray-800">86</h3>
                                <p class="text-sm font-semibold text-gray-500 mt-1">Bounced / Returned</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Dashboard Content Grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        
                        <!-- Recent Checks Table (Spans 2 columns) -->
                        <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <div class="px-8 py-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-800">Recent Transactions</h3>
                                    <p class="text-sm text-gray-500 font-medium">Latest checks entered into the system</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Search input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </div>
                                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out font-medium" placeholder="Search check #...">
                                    </div>
                                    <button class="p-2 border border-gray-200 bg-white rounded-xl text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto flex-1 p-2">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-gray-400 text-xs uppercase tracking-widest bg-white">
                                            <th class="px-6 py-4 font-bold border-b border-gray-100">Check Number</th>
                                            <th class="px-6 py-4 font-bold border-b border-gray-100">Payee/Bank</th>
                                            <th class="px-6 py-4 font-bold border-b border-gray-100">Date</th>
                                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-right">Amount</th>
                                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-center">Status</th>
                                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 text-sm">
                                        <tr class="hover:bg-blue-50/50 transition-colors group cursor-pointer">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-gray-800 bg-gray-100 inline-block px-2.5 py-1 rounded-lg">#CH-9201</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-gray-800 font-bold text-base">Acme Corporation</div>
                                                <div class="text-gray-500 font-medium text-xs mt-0.5">Bank of America</div>
                                            </td>
                                            <td class="px-6 py-5 text-gray-500 font-semibold">Oct 24, 2023</td>
                                            <td class="px-6 py-5 font-extrabold text-gray-800 text-right text-base">$4,500.00</td>
                                            <td class="px-6 py-5 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Cleared
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <button class="text-gray-400 hover:text-blue-600 transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-blue-50/50 transition-colors group cursor-pointer">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-gray-800 bg-gray-100 inline-block px-2.5 py-1 rounded-lg">#CH-9202</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-gray-800 font-bold text-base">Global Tech LLC</div>
                                                <div class="text-gray-500 font-medium text-xs mt-0.5">Chase Bank</div>
                                            </td>
                                            <td class="px-6 py-5 text-gray-500 font-semibold">Oct 23, 2023</td>
                                            <td class="px-6 py-5 font-extrabold text-gray-800 text-right text-base">$12,450.00</td>
                                            <td class="px-6 py-5 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 shadow-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <button class="text-gray-400 hover:text-blue-600 transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-blue-50/50 transition-colors group cursor-pointer">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-gray-800 bg-gray-100 inline-block px-2.5 py-1 rounded-lg">#CH-9198</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-gray-800 font-bold text-base">Summit Supplies</div>
                                                <div class="text-gray-500 font-medium text-xs mt-0.5">Wells Fargo</div>
                                            </td>
                                            <td class="px-6 py-5 text-gray-500 font-semibold">Oct 21, 2023</td>
                                            <td class="px-6 py-5 font-extrabold text-gray-800 text-right text-base">$850.50</td>
                                            <td class="px-6 py-5 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Cleared
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <button class="text-gray-400 hover:text-blue-600 transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-blue-50/50 transition-colors group cursor-pointer">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-gray-800 bg-gray-100 inline-block px-2.5 py-1 rounded-lg">#CH-9185</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-gray-800 font-bold text-base">Apex Marketing</div>
                                                <div class="text-gray-500 font-medium text-xs mt-0.5">Citibank</div>
                                            </td>
                                            <td class="px-6 py-5 text-gray-500 font-semibold">Oct 19, 2023</td>
                                            <td class="px-6 py-5 font-extrabold text-gray-800 text-right text-base">$3,200.00</td>
                                            <td class="px-6 py-5 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 shadow-sm">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Returned
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <button class="text-gray-400 hover:text-blue-600 transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination or View All Footer -->
                            <div class="p-4 border-t border-gray-50 bg-gray-50/30 text-center">
                                <a href="#" class="text-blue-600 font-bold text-sm hover:text-blue-700 hover:underline">View All Transactions &rarr;</a>
                            </div>
                        </div>

                        <!-- Right Column (Action Cards) -->
                        <div class="space-y-6 xl:col-span-1">
                            
                            <!-- Urgent Action Card -->
                            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-3xl shadow-lg p-8 text-white relative overflow-hidden group">
                                <div class="absolute -right-10 -top-10 bg-white/10 w-40 h-40 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
                                <h3 class="text-2xl font-extrabold mb-3 flex items-center gap-2">
                                    <svg class="w-7 h-7 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Action Required
                                </h3>
                                <p class="text-blue-100 font-medium mb-6 text-base leading-relaxed">There are <strong class="text-white text-lg">4 checks</strong> waiting for manual review before they can be batched to the bank.</p>
                                <button class="w-full bg-white text-blue-600 font-extrabold py-3.5 rounded-xl hover:bg-gray-50 transition-colors shadow-md transform hover:-translate-y-0.5 duration-200">
                                    Review Pending Checks
                                </button>
                            </div>

                            <!-- Bank Summary Box -->
                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                                <!-- Background pattern -->
                                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-gray-50 to-transparent pointer-events-none"></div>
                                <h3 class="text-lg font-extrabold text-gray-800 border-b border-gray-100 pb-4 mb-6 relative">Bank Discrepancies</h3>
                                <div class="space-y-5 relative">
                                    <div class="flex justify-between items-center group cursor-pointer p-2 -mx-2 rounded-xl hover:bg-gray-50 transition">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600 font-extrabold text-sm shadow-sm group-hover:scale-110 transition-transform">BofA</div>
                                            <div>
                                                <p class="text-base font-bold text-gray-800">Main Operating</p>
                                                <p class="text-xs font-semibold text-gray-500">Acct Ending ...4598</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-extrabold text-gray-800 text-lg">$142k</p>
                                            <p class="text-xs font-bold text-emerald-500 text-right">Matched</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center group cursor-pointer p-2 -mx-2 rounded-xl hover:bg-red-50 transition">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600 font-extrabold text-sm shadow-sm group-hover:scale-110 transition-transform">CH</div>
                                            <div>
                                                <p class="text-base font-bold text-gray-800">Payroll Account</p>
                                                <p class="text-xs font-semibold text-gray-500">Acct Ending ...1204</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-extrabold text-gray-800 text-lg">$85k</p>
                                            <p class="text-xs font-bold text-red-500 text-right">Mismatch</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-gray-100 relative">
                                    <button class="w-full border-2 border-dashed border-gray-300 text-gray-500 font-bold py-3 rounded-xl hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                                        + Add New Bank Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        @stack('modals')
        @livewireScripts
    </body>
</html>
