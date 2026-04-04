<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Professional Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar />

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-10 py-6 sticky top-0 z-10">
        <div>
            <h1 class="font-black text-3xl text-slate-800 tracking-tight">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Real-time system analytics and health monitoring</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-slate-800 font-bold">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">{{ auth()->user()->role ?? 'Administrator' }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-200">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto p-10 space-y-10">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total Received Card -->
            <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-arrow-down-long text-2xl"></i>
                        </div>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Received</h3>
                <p class="text-3xl font-black text-slate-800">LKR {{ number_format($totalReceivedValue, 2) }}</p>
            </div>

            <!-- Total Issued Card -->
            <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-arrow-up-long text-2xl"></i>
                        </div>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Issued</h3>
                <p class="text-3xl font-black text-slate-800">LKR {{ number_format($totalIssuedValue, 2) }}</p>
            </div>

            <!-- Net Balance Card -->
            <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-wallet text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Net Balance</h3>
                <p class="text-3xl font-black {{ $netBalance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    LKR {{ number_format($netBalance, 2) }}
                </p>
            </div>

            <!-- Total Users Card -->
            <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-violet-500/5 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-violet-50 flex items-center justify-center text-violet-600 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">System Users</h3>
                <p class="text-3xl font-black text-slate-800">{{ $userCount }}</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Money Flow Line Chart -->
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Value Flow Trend</h2>
                        <p class="text-slate-400 text-sm font-semibold uppercase tracking-wider mt-1">Comparison of Received vs Issued Valued Over 6 Months</p>
                    </div>
                </div>
                <div class="h-[400px]">
                    <canvas id="flowChart"></canvas>
                </div>
            </div>

            <!-- Cheque Status Distribution -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <h2 class="text-xl font-black text-slate-800 mb-8">Status Breakdown</h2>
                <div class="relative h-[300px]">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="mt-10 space-y-4">
                    <div class="flex justify-between items-center bg-slate-50/50 p-4 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>
                            <span class="font-bold text-slate-600">Pending</span>
                        </div>
                        <span class="font-black text-slate-800">{{ $pendingCheques }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50/50 p-4 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></div>
                            <span class="font-bold text-slate-600">Cleared</span>
                        </div>
                        <span class="font-black text-slate-800">{{ $realizedCheques }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50/50 p-4 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-rose-500 ring-4 ring-rose-100"></div>
                            <span class="font-bold text-slate-600">Bounced</span>
                        </div>
                        <span class="font-black text-slate-800">{{ $bouncedCheques }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-10">
            <!-- Recent Activity -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-black text-slate-800"><i class="fa-solid fa-bolt mr-2 text-amber-500"></i> Recent Activity</h3>
                    <span class="px-4 py-1 bg-slate-50 text-slate-500 rounded-full text-xs font-bold uppercase tracking-widest">Live Updates</span>
                </div>
                <div class="space-y-6">
                    @foreach($activities as $activity)
                        <div class="flex items-center gap-5 p-4 rounded-[1.5rem] hover:bg-slate-50 transition duration-200 group">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                                @if($activity->action == 'created')
                                    <i class="fa-solid fa-plus"></i>
                                @elseif($activity->action == 'updated')
                                    <i class="fa-solid fa-pen"></i>
                                @else
                                    <i class="fa-solid fa-circle-info"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-slate-800 font-bold leading-none mb-1">
                                    {{ $activity->user?->name ?? 'System' }} 
                                    <span class="text-slate-400 font-medium">recorded a </span>
                                    <span class="text-blue-600 capitalize">{{ $activity->action }}</span>
                                    <span class="text-slate-400 font-medium"> in </span>
                                    <span class="text-slate-600 capitalize">{{ $activity->module }}</span>
                                </p>
                                <p class="text-slate-400 text-sm font-semibold tracking-tight">{{ $activity->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-slate-800 font-black text-sm">{{ $activity->created_at->format('H:i') }}</p>
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bank Health Overview -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <h3 class="text-lg font-black text-slate-800 mb-8"><i class="fa-solid fa-building-columns mr-2 text-blue-600"></i> Bank Operational Health</h3>
                <div class="space-y-6">
                    @forelse($bankHealth as $bank)
                        <div class="p-6 rounded-[2rem] bg-slate-50/80 border border-slate-100 flex items-center justify-between group hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition duration-300">
                            <div>
                                <h4 class="text-slate-800 font-black text-lg">{{ $bank->bank_name }}</h4>
                                <p class="text-slate-400 text-sm font-semibold tracking-tight">{{ $bank->company_name }}</p>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <p class="text-slate-800 font-black text-2xl leading-none mb-1">{{ $bank->pending_count }}</p>
                                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-none">Pending Cheques</p>
                                </div>
                                <div class="w-12 h-2 rounded-full bg-slate-200 overflow-hidden">
                                    <div class="h-full bg-blue-600 transition-all duration-1000" style="width: {{ min(100, $bank->pending_count * 10) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <p class="text-slate-400 font-bold">No bank accounts linked</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </main>
</div>

<!-- Charts Script -->
<script>
    // Config Chart.js defaults
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // Money Flow Chart
    const flowCtx = document.getElementById('flowChart').getContext('2d');
    const flowGradientReceived = flowCtx.createLinearGradient(0, 0, 0, 400);
    flowGradientReceived.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
    flowGradientReceived.addColorStop(1, 'rgba(59, 130, 246, 0)');

    const flowGradientIssued = flowCtx.createLinearGradient(0, 0, 0, 400);
    flowGradientIssued.addColorStop(0, 'rgba(244, 63, 94, 0.2)');
    flowGradientIssued.addColorStop(1, 'rgba(244, 63, 94, 0)');

    new Chart(flowCtx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: 'Received Value',
                    data: @json($receivedTrends),
                    borderColor: '#3b82f6',
                    backgroundColor: flowGradientReceived,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 3
                },
                {
                    label: 'Issued Value',
                    data: @json($issuedTrends),
                    borderColor: '#f43f5e',
                    backgroundColor: flowGradientIssued,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f43f5e',
                    pointBorderWidth: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 15,
                    cornerRadius: 15,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: {
                        font: { weight: 'bold' },
                        callback: function(value) { return 'LKR ' + value.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { weight: 'bold' } }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Cleared', 'Bounced'],
            datasets: [{
                data: [{{ $pendingCheques }}, {{ $realizedCheques }}, {{ $bouncedCheques }}],
                backgroundColor: ['#3b82f6', '#10b981', '#f43f5e'],
                hoverOffset: 15,
                borderWidth: 0,
                spacing: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
</body>
</html>
