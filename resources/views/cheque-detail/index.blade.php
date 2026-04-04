@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cheque Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="text-gray-900 bg-gray-50 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar/>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden"
     x-data="{ openModal: false, viewModal: false, editModal: false, statusModal: false, selectedCheque: null }">

    <!-- Header -->
    <header
        class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-10">
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
                        <th class="px-6 py-4">Cheque Type</th>
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

                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $cheque->cheque_type === 'received' ? 'Received' : 'Issued' }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-700">
                                {{ $cheque->bank_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ Carbon::parse($cheque->cheque_date)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right font-bold">
                                {{ number_format($cheque->cheque_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($cheque->status == 'cleared')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Cleared</span>
                                @elseif($cheque->status == 'pending')
                                    <span
                                        class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Pending</span>
                                @elseif($cheque->status == 'bounced')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Bounced</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">
                                        {{ ucfirst($cheque->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-x-2">
                                    <!-- View Button -->
                                    <button
                                        @click="selectedCheque = {{ $cheque->toJson() }}; viewModal = true"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold hover:bg-blue-200 transition">
                                        View
                                    </button>

                                    <!-- Edit Button -->
                                    <button
                                        @click="selectedCheque = {{ $cheque->toJson() }}; editModal = true"
                                        class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold hover:bg-yellow-200 transition">
                                        Edit
                                    </button>

                                    <!-- Change Status Dropdown -->
                                    <div x-data="{
                                                    openStatus: false,
                                                    selectedStatus: '{{ $cheque->status }}',
                                                    dropdownTop: 0,
                                                    dropdownLeft: 0,
                                                    toggleDropdown(event) {
                                                        this.dropdownTop = event.target.getBoundingClientRect().top - 8; // slightly above
                                                        this.dropdownLeft = event.target.getBoundingClientRect().left;
                                                        this.openStatus = !this.openStatus;
                                                    }
                                                }" class="relative inline-block">

                                        <!-- Change Status Button -->
                                        <button @click="toggleDropdown($event)"
                                                class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold hover:bg-green-200 transition">
                                            Change Status
                                        </button>

                                        <!-- Dropdown -->
                                        <div x-show="openStatus"
                                             @click.away="openStatus = false"
                                             x-transition
                                             style="position: fixed; top: 0; left: 0; z-index: 9999;"
                                             :style="`top: ${dropdownTop}px; left: ${dropdownLeft}px;`"
                                             class="w-36 bg-white border border-gray-200 rounded-lg shadow-lg">

                                            <template
                                                x-for="status in {{ $cheque->cheque_type === 'received' ? json_encode(['pending','deposited','cleared','bounced']) : json_encode(['pending','issued','cleared','cancelled']) }}"
                                                :key="status">
                                                <form :action="`/cheques/{{ $cheque->id }}/status`" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" :value="status">
                                                    <button type="submit"
                                                            class="w-full text-left px-4 py-2 hover:bg-green-100 transition text-sm font-medium"
                                                            x-text="status.charAt(0).toUpperCase() + status.slice(1)">
                                                    </button>
                                                </form>
                                            </template>
                                        </div>
                                    </div>
                                </div>
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
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">

            <div @click.away="openModal = false"
                 class="bg-white rounded-2xl w-full max-w-2xl shadow-xl flex flex-col max-h-[90vh]">

                <!-- Scrollable content -->
                <div class="overflow-y-auto p-6">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Add New Cheque</h3>
                        <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                    </div>

                    <form method="POST" action="{{ route('cheques.store') }}"
                          x-data="{
                    chequeType: 'issued',
                    statuses: {
                        issued: ['pending', 'issued', 'cleared', 'cancelled'],
                        received: ['pending', 'deposited', 'cleared', 'bounced']
                    }
                  }">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">

                            <!-- Cheque No -->
                            <div class="flex flex-col">
                                <label for="cheque_no" class="mb-1 text-gray-700 font-medium">
                                    Cheque No <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cheque_no" id="cheque_no" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Date -->
                            <div class="flex flex-col">
                                <label for="cheque_date" class="mb-1 text-gray-700 font-medium">
                                    Cheque Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="cheque_date" id="cheque_date" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Expiry Date -->
                            <div class="flex flex-col">
                                <label for="cheque_exp_date" class="mb-1 text-gray-700 font-medium">Cheque Expiry
                                    Date</label>
                                <input type="date" name="cheque_exp_date" id="cheque_exp_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Deposit Type -->
                            <div x-data="{ chequeType: '', cashPerson: '' }" class="flex flex-col">
                                <!-- Cheque Deposit Type -->
                                <label for="cheque_type" class="mb-1 text-gray-700 font-medium">
                                    Cheque Deposit Type <span class="text-red-500">*</span>
                                </label>
                                <select x-model="chequeType" name="cheque_type" id="cheque_type" required
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="" disabled selected>Select cheque type</option>
                                    <option value="cross">Cross</option>
                                    <option value="cash">Cash</option>
                                </select>

                                <!-- Conditional input for Cash cheques -->
                                <div x-show="chequeType === 'cash'" class="flex flex-col mt-2">
                                    <label for="cash_person" class="mb-1 text-gray-700 font-medium">
                                        Person Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="cashPerson" id="cash_person"
                                           placeholder="Enter person name"
                                           class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                           :required="chequeType === 'cash'">
                                </div>

                                <!-- Hidden input to combine values for backend -->
                                <input type="hidden" name="cheque_type_combined"
                                       :value="chequeType === 'cash' ? `Cash - ${cashPerson}` : chequeType">
                            </div>

                            <!-- Bank Account Dropdown -->
                            <div class="flex flex-col">
                                <label for="bank_account_id" class="mb-1 text-gray-700 font-medium">
                                    Company Bank Account <span class="text-red-500">*</span>
                                </label>
                                <select name="bank_account_id" id="bank_account_id" required
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="" disabled selected>Select Bank Account</option>
                                    @foreach($bankAccounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->bank_name }} - {{ $account->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cheque Amount -->
                            <div class="flex flex-col">
                                <label for="cheque_amount" class="mb-1 text-gray-700 font-medium">
                                    Amount <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="cheque_amount" id="cheque_amount" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Type -->
                            <div class="flex flex-col">
                                <label for="cheque_type" class="mb-1 text-gray-700 font-medium">
                                    Cheque Type <span class="text-red-500">*</span>
                                </label>
                                <select name="cheque_type" id="cheque_type" x-model="chequeType"
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="issued">Issued</option>
                                    <option value="received">Received</option>
                                </select>
                            </div>

                            <!-- Dynamic Status -->
                            <div class="flex flex-col">
                                <label for="status" class="mb-1 text-gray-700 font-medium">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status"
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <template x-for="status in statuses[chequeType]" :key="status">
                                        <option :value="status"
                                                x-text="status.charAt(0).toUpperCase() + status.slice(1)"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Deposit Date -->
                            <div class="flex flex-col">
                                <label for="deposit_date" class="mb-1 text-gray-700 font-medium">Deposit Date</label>
                                <input type="date" name="deposit_date" id="deposit_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Realization Date -->
                            <div class="flex flex-col">
                                <label for="realization_date" class="mb-1 text-gray-700 font-medium">Realization
                                    Date</label>
                                <input type="date" name="realization_date" id="realization_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Bounce Date -->
                            <div class="flex flex-col">
                                <label for="bounce_date" class="mb-1 text-gray-700 font-medium">Bounce Date</label>
                                <input type="date" name="bounce_date" id="bounce_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Bank Charges -->
                            <div class="flex flex-col">
                                <label for="bank_charges" class="mb-1 text-gray-700 font-medium">Bank Charges</label>
                                <input type="number" step="0.01" name="bank_charges" id="bank_charges" value="0"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Penalty Amount -->
                            <div class="flex flex-col">
                                <label for="penalty_amount" class="mb-1 text-gray-700 font-medium">Penalty
                                    Amount</label>
                                <input type="number" step="0.01" name="penalty_amount" id="penalty_amount" value="0"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Reference No -->
                            <div class="flex flex-col">
                                <label for="reference_no" class="mb-1 text-gray-700 font-medium">Reference No</label>
                                <input type="text" name="reference_no" id="reference_no"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Remarks -->
                            <div class="flex flex-col col-span-2">
                                <label for="remarks" class="mb-1 text-gray-700 font-medium">Remarks</label>
                                <textarea name="remarks" id="remarks" placeholder="Remarks"
                                          class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                            </div>

                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="openModal = false"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition">
                                Save Cheque
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- VIEW CHEQUE MODAL -->
        <div x-show="viewModal" x-transition.opacity x-cloak
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div @click.away="viewModal = false" class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-xl">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Cheque Details</h3>
                    <button @click="viewModal = false" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                </div>

                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <div><strong>Cheque No:</strong> <span x-text="selectedCheque?.cheque_no"></span></div>
                    <div><strong>Type:</strong> <span
                            x-text="selectedCheque?.cheque_type === 'received' ? 'Received' : 'Issued'"></span></div>
                    <div><strong>Bank:</strong> <span x-text="selectedCheque?.bank_name"></span></div>
                    <div><strong>Branch:</strong> <span x-text="selectedCheque?.branch_name"></span></div>
                    <div><strong>Account No:</strong> <span x-text="selectedCheque?.account_no"></span></div>
                    <div><strong>Amount:</strong> <span
                            x-text="parseFloat(selectedCheque?.cheque_amount).toFixed(2)"></span></div>
                    <div><strong>Cheque Date:</strong> <span
                            x-text="new Date(selectedCheque?.cheque_date).toLocaleDateString()"></span></div>
                    <div><strong>Expiry Date:</strong> <span
                            x-text="selectedCheque?.cheque_exp_date ? new Date(selectedCheque.cheque_exp_date).toLocaleDateString() : '-'"></span>
                    </div>
                    <div><strong>Status:</strong> <span
                            x-text="selectedCheque?.status.charAt(0).toUpperCase() + selectedCheque?.status.slice(1)"></span>
                    </div>
                    <div><strong>Deposit Date:</strong> <span
                            x-text="selectedCheque?.deposit_date ? new Date(selectedCheque.deposit_date).toLocaleDateString() : '-'"></span>
                    </div>
                    <div><strong>Realization Date:</strong> <span
                            x-text="selectedCheque?.realization_date ? new Date(selectedCheque.realization_date).toLocaleDateString() : '-'"></span>
                    </div>
                    <div><strong>Bounce Date:</strong> <span
                            x-text="selectedCheque?.bounce_date ? new Date(selectedCheque.bounce_date).toLocaleDateString() : '-'"></span>
                    </div>
                    <div class="col-span-2"><strong>Remarks:</strong> <span
                            x-text="selectedCheque?.remarks || '-'"></span></div>
                </div>

                <div class="flex justify-end mt-6">
                    <button @click="viewModal = false"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">
                        Close
                    </button>
                </div>

            </div>
        </div>

    </main>
</div>

@livewireScripts

</body>
</html>
