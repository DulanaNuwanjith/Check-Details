<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar />

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-10">
        <h1 class="font-extrabold text-2xl text-gray-800">Dashboard</h1>
        <div class="text-gray-600 font-medium">Welcome, {{ auth()->user()->name }}</div>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto p-8">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Bank Accounts Card -->
            <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
                <h2 class="text-gray-500 text-sm font-medium">Bank Accounts</h2>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAccounts }}</p>
                <div class="flex justify-between mt-2 text-sm text-gray-600">
                    <span>Active: {{ $activeAccounts }}</span>
                    <span>Inactive: {{ $inactiveAccounts }}</span>
                </div>
            </div>

            <!-- Cheques Card -->
            <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
                <h2 class="text-gray-500 text-sm font-medium">Cheques</h2>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCheques }}</p>
                <div class="flex justify-between mt-2 text-sm text-gray-600">
                    <span>Pending: {{ $pendingCheques }}</span>
                    <span>Realized: {{ $realizedCheques }}</span>
                    <span>Bounced: {{ $bouncedCheques }}</span>
                </div>
            </div>

            <!-- Other Metrics Card -->
            <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
                <h2 class="text-gray-500 text-sm font-medium">Other Metrics</h2>
                <p class="text-2xl font-bold text-gray-800">--</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
                <h2 class="text-gray-500 font-medium mb-4">Cheque Status</h2>
                <canvas id="chequeChart" class="w-full h-64"></canvas>
            </div>

            <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
                <h2 class="text-gray-500 font-medium mb-4">Accounts Status</h2>
                <canvas id="accountChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <!-- Activity Log Table -->
        <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
            <h2 class="text-gray-500 font-medium mb-4">Recent Activity</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-2 text-left">Time</th>
                        <th class="px-4 py-2 text-left">User</th>
                        <th class="px-4 py-2 text-left">Action</th>
                        <th class="px-4 py-2 text-left">Module</th>
                        <th class="px-4 py-2 text-left">Description</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $activity->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $activity->user?->name ?? 'System' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 capitalize">{{ $activity->action }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 capitalize">{{ $activity->module }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $activity->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Charts Script -->
<script>
    const chequeCtx = document.getElementById('chequeChart').getContext('2d');
    new Chart(chequeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Realized', 'Bounced'],
            datasets: [{
                label: 'Cheques',
                data: [{{ $pendingCheques }}, {{ $realizedCheques }}, {{ $bouncedCheques }}],
                backgroundColor: ['#facc15', '#22c55e', '#ef4444'],
            }]
        },
    });

    const accountCtx = document.getElementById('accountChart').getContext('2d');
    new Chart(accountCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                label: 'Accounts',
                data: [{{ $activeAccounts }}, {{ $inactiveAccounts }}],
                backgroundColor: ['#3b82f6', '#9ca3af'],
            }]
        },
    });
</script>
</body>
</html>
