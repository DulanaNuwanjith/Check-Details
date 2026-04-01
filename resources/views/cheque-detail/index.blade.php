<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cheque Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="text-gray-900 bg-gray-50 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar />

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden" x-data="{ openModal: false }">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-10">
        <div>
            <h1 class="font-extrabold text-2xl text-gray-800">Cheque Management</h1>
            <p class="text-sm text-gray-500 font-medium">Manage issued and received cheques</p>
        </div>

        <button @click="openModal = true"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold shadow hover:bg-blue-700 transition">
            + Add New Cheque
        </button>
    </header>

    <!-- Content -->
    <main class="flex-1 overflow-y-auto p-8">

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Cheque No</th>
                        <th class="px-6 py-4">Bank</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">

                    @forelse($cheques as $cheque)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">
                                #{{ $cheque->cheque_no }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-700">
                                {{ $cheque->bank_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($cheque->cheque_date)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right font-bold">
                                {{ number_format($cheque->cheque_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($cheque->status == 'cleared')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Cleared</span>
                                @elseif($cheque->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Pending</span>
                                @elseif($cheque->status == 'bounced')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Bounced</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                        {{ ucfirst($cheque->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-500 hover:underline text-sm">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">
                                No cheques found
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="openModal"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div @click.away="openModal = false"
                 class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-xl">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Add New Cheque</h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                </div>

                <form method="POST" action="{{ route('cheques.store') }}"
                      x-data="{
                chequeType: 'received',
                statuses: {
                    received: ['pending', 'deposited', 'cleared', 'bounced'],
                    issued: ['pending', 'issued', 'cleared', 'cancelled']
                }
              }">

                    @csrf

                    <div class="grid grid-cols-2 gap-4">

                        <!-- Cheque No -->
                        <div class="flex flex-col">
                            <label for="cheque_no" class="mb-1 text-gray-700 font-medium">Cheque No</label>
                            <input type="text" name="cheque_no" id="cheque_no" required
                                   class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        <!-- Cheque Date -->
                        <div class="flex flex-col">
                            <label for="cheque_date" class="mb-1 text-gray-700 font-medium">Cheque Date</label>
                            <input type="date" name="cheque_date" id="cheque_date" required
                                   class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        <!-- Cheque Expiry Date (optional) -->
                        <div class="flex flex-col">
                            <label for="cheque_exp_date" class="mb-1 text-gray-700 font-medium">Cheque Expiry Date</label>
                            <input type="date" name="cheque_exp_date" id="cheque_exp_date"
                                   class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        <!-- Bank Account Dropdown -->
                        <div class="flex flex-col">
                            <label for="bank_account_id" class="mb-1 text-gray-700 font-medium">Bank Account</label>
                            <select name="bank_account_id" id="bank_account_id" required
                                    class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="" disabled selected>Select Bank Account</option>
                                @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->bank_name }} - {{ $account->account_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cheque Amount -->
                        <div class="flex flex-col">
                            <label for="cheque_amount" class="mb-1 text-gray-700 font-medium">Amount</label>
                            <input type="number" step="0.01" name="cheque_amount" id="cheque_amount" required
                                   class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        <!-- Cheque Type -->
                        <div class="flex flex-col">
                            <label for="cheque_type" class="mb-1 text-gray-700 font-medium">Cheque Type</label>
                            <select name="cheque_type" id="cheque_type" x-model="chequeType"
                                    class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="received">Received</option>
                                <option value="issued">Issued</option>
                            </select>
                        </div>

                        <!-- Dynamic Status -->
                        <div class="flex flex-col">
                            <label for="status" class="mb-1 text-gray-700 font-medium">Status</label>
                            <select name="status" id="status"
                                    class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <template x-for="status in statuses[chequeType]" :key="status">
                                    <option :value="status" x-text="status.charAt(0).toUpperCase() + status.slice(1)"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div class="flex flex-col col-span-2">
                            <label for="remarks" class="mb-1 text-gray-700 font-medium">Remarks</label>
                            <textarea name="remarks" id="remarks" placeholder="Remarks"
                                      class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <!-- Cancel Button -->
                        <button type="button" @click="openModal = false"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">
                            Cancel
                        </button>

                        <!-- Save Button -->
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition">
                            Save Cheque
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
</div>

@livewireScripts

</body>
</html>
