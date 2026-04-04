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

<!-- Success Message -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#16a34a'
            });
        });
    </script>
@endif

<!-- Error Message -->
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc2626'
            });
        });
    </script>
@endif

<!-- Validation Errors -->
@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let errors = @json($errors->all(), JSON_THROW_ON_ERROR);
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errors.join('<br>'),
                confirmButtonColor: '#dc2626'
            });
        });
    </script>
@endif

<body class="text-gray-900 bg-gray-50 flex h-screen overflow-hidden antialiased">

<!-- Sidebar -->
<x-sidebar/>

<!-- Main Content -->
<div class="flex-1 flex flex-col h-screen overflow-hidden"
     x-data="{ 
        openModal: false, 
        viewModal: false, 
        selectedCheque: null,
        statuses: {
            issued: ['pending', 'issued', 'cleared', 'cancelled'],
            received: ['pending', 'deposited', 'cleared', 'bounced']
        },
        chequeForm: {
            id: '',
            cheque_no: '',
            cheque_date: '',
            cheque_exp_date: '',
            bank_account_id: '',
            cheque_amount: '',
            cheque_type: 'issued',
            status: 'pending',
            deposit_date: '',
            realization_date: '',
            bounce_date: '',
            bank_charges: '0',
            penalty_amount: '0',
            reference_no: '',
            remarks: '',
            cheque_deposit_type: '',
            cash_person: ''
        },
        resetForm() {
            this.chequeForm = {
                id: '',
                cheque_no: '',
                cheque_date: '',
                cheque_exp_date: '',
                bank_account_id: '',
                cheque_amount: '',
                cheque_type: 'issued',
                status: 'pending',
                deposit_date: '',
                realization_date: '',
                bounce_date: '',
                bank_charges: '0',
                penalty_amount: '0',
                reference_no: '',
                remarks: '',
                cheque_deposit_type: '',
                cash_person: ''
            };
        }
     }">

    <!-- Header -->
    <header
        class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-10">
        <div>
            <h1 class="font-extrabold text-2xl text-gray-800">Cheque Management</h1>
            <p class="text-sm text-gray-500 font-medium">Manage issued and received cheques</p>
        </div>

        <button @click="resetForm(); openModal = true"
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
                                {{ $cheque->bank_name }} - {{ $cheque->company_name }}
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
                                        @click="
                                            let c = {{ $cheque->toJson() }};
                                            chequeForm = {
                                                id: c.id,
                                                cheque_no: c.cheque_no,
                                                cheque_date: c.cheque_date ? c.cheque_date.split(' ')[0] : '',
                                                cheque_exp_date: c.cheque_exp_date ? c.cheque_exp_date.split(' ')[0] : '',
                                                bank_account_id: c.bank_account_id,
                                                cheque_amount: c.cheque_amount,
                                                cheque_type: c.cheque_type,
                                                status: c.status,
                                                deposit_date: c.deposit_date ? c.deposit_date.split(' ')[0] : '',
                                                realization_date: c.realization_date ? c.realization_date.split(' ')[0] : '',
                                                bounce_date: c.bounce_date ? c.bounce_date.split(' ')[0] : '',
                                                bank_charges: c.bank_charges,
                                                penalty_amount: c.penalty_amount,
                                                reference_no: c.reference_no,
                                                remarks: c.remarks
                                            };
                                            let crossType = c.cheque_type_cross_cheque || '';
                                            if (crossType.startsWith('Cash - ')) {
                                                chequeForm.cheque_deposit_type = 'cash';
                                                chequeForm.cash_person = crossType.replace('Cash - ', '');
                                            } else {
                                                chequeForm.cheque_deposit_type = crossType;
                                                chequeForm.cash_person = '';
                                            }
                                            openModal = true;
                                        "
                                        class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold hover:bg-yellow-200 transition">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('cheques.destroy', $cheque->id) }}"
                                          onsubmit="confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold hover:bg-red-200 transition">
                                            Delete
                                        </button>
                                    </form>
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
                        <h3 class="text-xl font-bold" x-text="chequeForm.id ? 'Edit Cheque' : 'Add New Cheque'"></h3>
                        <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
                    </div>

                    <form method="POST" :action="chequeForm.id ? `/cheques/${chequeForm.id}` : '{{ route('cheques.store') }}'">
                        @csrf
                        <template x-if="chequeForm.id">
                            <input type="hidden" name="_method" value="PATCH">
                        </template>

                        <div class="grid grid-cols-2 gap-4">

                            <!-- Cheque No -->
                            <div class="flex flex-col">
                                <label for="cheque_no" class="mb-1 text-gray-700 font-medium">
                                    Cheque No <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cheque_no" id="cheque_no" x-model="chequeForm.cheque_no" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Date -->
                            <div class="flex flex-col">
                                <label for="cheque_date" class="mb-1 text-gray-700 font-medium">
                                    Cheque Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="cheque_date" id="cheque_date" x-model="chequeForm.cheque_date" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Expiry Date -->
                            <div class="flex flex-col">
                                <label for="cheque_exp_date" class="mb-1 text-gray-700 font-medium">Cheque Expiry
                                    Date</label>
                                <input type="date" name="cheque_exp_date" id="cheque_exp_date" x-model="chequeForm.cheque_exp_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Deposit Type -->
                            <div class="flex flex-col">
                                <label for="cheque_deposit_type" class="mb-1 text-gray-700 font-medium">
                                    Cheque Deposit Type <span class="text-red-500">*</span>
                                </label>
                                <select x-model="chequeForm.cheque_deposit_type" id="cheque_deposit_type" required
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="" disabled selected>Select cheque type</option>
                                    <option value="cross">Cross</option>
                                    <option value="cash">Cash</option>
                                </select>

                                <!-- Conditional input for Cash cheques -->
                                <div x-show="chequeForm.cheque_deposit_type === 'cash'" class="flex flex-col mt-2">
                                    <label for="cash_person" class="mb-1 text-gray-700 font-medium">
                                        Person Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="chequeForm.cash_person" id="cash_person"
                                           placeholder="Enter person name"
                                           class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                           :required="chequeForm.cheque_deposit_type === 'cash'">
                                </div>

                                <!-- Hidden input to combine values for backend -->
                                <input type="hidden" name="cheque_type_combined"
                                       :value="chequeForm.cheque_deposit_type === 'cash' ? `Cash - ${chequeForm.cash_person}` : chequeForm.cheque_deposit_type">
                            </div>

                            <!-- Bank Account Dropdown -->
                            <div class="flex flex-col">
                                <label for="bank_account_id" class="mb-1 text-gray-700 font-medium">
                                    Company Bank Account <span class="text-red-500">*</span>
                                </label>
                                <select name="bank_account_id" id="bank_account_id" x-model="chequeForm.bank_account_id" required
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
                                <input type="number" step="0.01" name="cheque_amount" id="cheque_amount" x-model="chequeForm.cheque_amount" required
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Cheque Type -->
                            <div class="flex flex-col">
                                <label for="cheque_type" class="mb-1 text-gray-700 font-medium">
                                    Cheque Type <span class="text-red-500">*</span>
                                </label>
                                <select name="cheque_type" id="cheque_type" x-model="chequeForm.cheque_type"
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
                                <select name="status" id="status" x-model="chequeForm.status"
                                        class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <template x-for="status in statuses[chequeForm.cheque_type]" :key="status">
                                        <option :value="status"
                                                x-text="status.charAt(0).toUpperCase() + status.slice(1)"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Deposit Date -->
                            <div class="flex flex-col">
                                <label for="deposit_date" class="mb-1 text-gray-700 font-medium">Deposit Date</label>
                                <input type="date" name="deposit_date" id="deposit_date" x-model="chequeForm.deposit_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Realization Date -->
                            <div class="flex flex-col">
                                <label for="realization_date" class="mb-1 text-gray-700 font-medium">Realization
                                    Date</label>
                                <input type="date" name="realization_date" id="realization_date" x-model="chequeForm.realization_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Bounce Date -->
                            <div class="flex flex-col">
                                <label for="bounce_date" class="mb-1 text-gray-700 font-medium">Bounce Date</label>
                                <input type="date" name="bounce_date" id="bounce_date" x-model="chequeForm.bounce_date"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Bank Charges -->
                            <div class="flex flex-col">
                                <label for="bank_charges" class="mb-1 text-gray-700 font-medium">Bank Charges</label>
                                <input type="number" step="0.01" name="bank_charges" id="bank_charges" x-model="chequeForm.bank_charges"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Penalty Amount -->
                            <div class="flex flex-col">
                                <label for="penalty_amount" class="mb-1 text-gray-700 font-medium">Penalty
                                    Amount</label>
                                <input type="number" step="0.01" name="penalty_amount" id="penalty_amount" x-model="chequeForm.penalty_amount"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Reference No -->
                            <div class="flex flex-col">
                                <label for="reference_no" class="mb-1 text-gray-700 font-medium">Reference No</label>
                                <input type="text" name="reference_no" id="reference_no" x-model="chequeForm.reference_no"
                                       class="p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Remarks -->
                            <div class="flex flex-col col-span-2">
                                <label for="remarks" class="mb-1 text-gray-700 font-medium">Remarks</label>
                                <textarea name="remarks" id="remarks" placeholder="Remarks" x-model="chequeForm.remarks"
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
                                <span x-text="chequeForm.id ? 'Update Cheque' : 'Save Cheque'"></span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- VIEW CHEQUE MODAL -->
        <div x-show="viewModal" x-transition.opacity x-cloak
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

            <div @click.away="viewModal = false"
                 class="bg-white rounded-[2.5rem] w-full max-w-4xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh] border border-white/20">

                <!-- Header -->
                <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Cheque #<span x-text="selectedCheque?.cheque_no"></span></h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Detailed View & Operations</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <template x-if="selectedCheque?.status === 'cleared'">
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black uppercase tracking-widest ring-4 ring-emerald-50">Cleared</span>
                        </template>
                        <template x-if="selectedCheque?.status === 'pending'">
                            <span class="px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-black uppercase tracking-widest ring-4 ring-amber-50">Pending</span>
                        </template>
                        <template x-if="selectedCheque?.status === 'bounced'">
                            <span class="px-4 py-1.5 bg-rose-100 text-rose-700 rounded-full text-xs font-black uppercase tracking-widest ring-4 ring-rose-50">Bounced</span>
                        </template>
                        <button @click="viewModal = false" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center text-xl font-bold">✕</button>
                    </div>
                </div>

                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto p-10 space-y-10 custom-scrollbar">
                    
                    <!-- Hero Section (Amount & Type) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-2 p-8 rounded-[2rem] bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden shadow-xl shadow-slate-200">
                             <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                             <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                             
                             <h4 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Total Cheque Amount</h4>
                             <p class="text-5xl font-black tracking-tighter mb-4">LKR <span x-text="parseFloat(selectedCheque?.cheque_amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></p>
                             
                             <div class="flex items-center gap-3">
                                <template x-if="selectedCheque?.cheque_type === 'received'">
                                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/20 text-emerald-400 rounded-2xl border border-emerald-500/20 backdrop-blur-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-black text-xs uppercase tracking-widest">Received Cheque (IN)</span>
                                    </div>
                                </template>
                                <template x-if="selectedCheque?.cheque_type !== 'received'">
                                    <div class="flex items-center gap-2 px-4 py-2 bg-rose-500/20 text-rose-400 rounded-2xl border border-rose-500/20 backdrop-blur-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-black text-xs uppercase tracking-widest">Issued Cheque (OUT)</span>
                                    </div>
                                </template>
                                <div class="px-4 py-2 bg-white/5 text-slate-300 rounded-2xl border border-white/5 font-black text-xs uppercase tracking-widest">
                                    <span x-text="selectedCheque?.cheque_type_cross_cheque || 'Regular'"></span>
                                </div>
                             </div>
                        </div>
                        
                        <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 flex flex-col justify-center text-center group hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition duration-300">
                            <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-4 leading-none">Days Until Expiry</h4>
                            <template x-if="selectedCheque?.cheque_exp_date">
                                <div>
                                    <p class="text-5xl font-black text-slate-800 tracking-tighter mb-1" x-text="Math.max(0, Math.ceil((new Date(selectedCheque.cheque_exp_date) - new Date()) / (1000 * 60 * 60 * 24)))"></p>
                                    <p class="text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">Days Remaining</p>
                                </div>
                            </template>
                            <template x-if="!selectedCheque?.cheque_exp_date">
                                <p class="text-slate-300 font-black italic">No Expiry Set</p>
                            </template>
                        </div>
                    </div>

                    <!-- Detailed Info Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        
                        <!-- Bank Info Column -->
                        <div class="space-y-8">
                            <div>
                                <h5 class="text-slate-800 font-black text-sm uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-600 ring-4 ring-blue-100"></span>
                                    Bank Profile Details
                                </h5>
                                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-sm space-y-6">
                                    <div class="flex justify-between items-center group">
                                        <span class="text-slate-400 font-bold text-sm uppercase tracking-wider">Bank Name</span>
                                        <span class="text-slate-800 font-black flex items-center gap-2 group-hover:text-blue-600 transition" x-text="selectedCheque?.bank_name"></span>
                                    </div>
                                    <div class="w-full h-px bg-slate-50"></div>
                                    <div class="flex justify-between items-center group">
                                        <span class="text-slate-400 font-bold text-sm uppercase tracking-wider">Branch</span>
                                        <span class="text-slate-800 font-black group-hover:text-blue-600 transition" x-text="selectedCheque?.branch_name || 'Main Branch'"></span>
                                    </div>
                                    <div class="w-full h-px bg-slate-50"></div>
                                    <div class="flex justify-between items-center group">
                                        <span class="text-slate-400 font-bold text-sm uppercase tracking-wider">Associated Company</span>
                                        <span class="text-slate-800 font-black group-hover:text-blue-600 transition" x-text="selectedCheque?.company_name || 'N/A'"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Financial Adjustments -->
                            <div>
                                <h5 class="text-slate-800 font-black text-sm uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 ring-4 ring-rose-100"></span>
                                    Financial Operations
                                </h5>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition duration-300">
                                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-3 leading-none">Bank Charges</p>
                                        <p class="text-2xl font-black text-slate-800 tracking-tight">LKR <span x-text="parseFloat(selectedCheque?.bank_charges || 0).toFixed(2)"></span></p>
                                    </div>
                                    <div class="p-6 rounded-[2rem] bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-rose-500/5 transition duration-300">
                                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-3 leading-none">Penalty Fees</p>
                                        <p class="text-2xl font-black text-rose-600 tracking-tight">LKR <span x-text="parseFloat(selectedCheque?.penalty_amount || 0).toFixed(2)"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dates and Timeline -->
                        <div class="space-y-8">
                             <div>
                                <h5 class="text-slate-800 font-black text-sm uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                                    Processing Timeline
                                </h5>
                                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-sm">
                                    <div class="space-y-8 relative after:content-[''] after:absolute after:left-[23px] after:top-[40px] after:bottom-[40px] after:w-0.5 after:bg-slate-100 after:rounded-full">
                                        <!-- Issue Date -->
                                        <div class="flex items-start gap-6 relative z-10">
                                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black shadow-sm ring-4 ring-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div class="flex-1 -mt-1">
                                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Issue/Transaction Date</p>
                                                <p class="text-slate-800 font-black text-xl tracking-tight" x-text="selectedCheque?.cheque_date ? new Date(selectedCheque.cheque_date).toLocaleDateString() : 'N/A'"></p>
                                                <p class="text-slate-400 text-xs font-semibold mt-1">Official date written on the cheque</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Deposit Date -->
                                        <div class="flex items-start gap-6 relative z-10">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black shadow-sm ring-4 ring-white" :class="selectedCheque?.deposit_date ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-300'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                            </div>
                                            <div class="flex-1 -mt-1">
                                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Bank Deposit Date</p>
                                                <p class="text-slate-800 font-black text-xl tracking-tight" x-text="selectedCheque?.deposit_date ? new Date(selectedCheque.deposit_date).toLocaleDateString() : '---'"></p>
                                                <p class="text-slate-400 text-xs font-semibold mt-1" x-text="selectedCheque?.deposit_date ? 'Successfully deposited to the bank' : 'Awaiting deposit to bank'"></p>
                                            </div>
                                        </div>

                                        <!-- Final Status Date -->
                                        <div class="flex items-start gap-6 relative z-10">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black shadow-sm ring-4 ring-white" 
                                                 :class="selectedCheque?.status === 'cleared' ? 'bg-emerald-50 text-emerald-600' : (selectedCheque?.status === 'bounced' ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-300')">
                                                <template x-if="selectedCheque?.status === 'cleared'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </template>
                                                <template x-if="selectedCheque?.status === 'bounced'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </template>
                                                <template x-if="selectedCheque?.status !== 'cleared' && selectedCheque?.status !== 'bounced'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                </template>
                                            </div>
                                            <div class="flex-1 -mt-1">
                                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Final Realization Date</p>
                                                <p class="text-slate-800 font-black text-xl tracking-tight" x-text="selectedCheque?.realization_date ? new Date(selectedCheque.realization_date).toLocaleDateString() : (selectedCheque?.bounce_date ? new Date(selectedCheque.bounce_date).toLocaleDateString() : '---')"></p>
                                                <p class="text-slate-400 text-xs font-semibold mt-1" x-text="selectedCheque?.status === 'cleared' ? 'Funds fully cleared and realized' : (selectedCheque?.status === 'bounced' ? 'Cheque was returned/bounced' : 'Processing for realization')"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <div class="px-8 py-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-start gap-6 group hover:bg-white hover:shadow-xl hover:shadow-blue-500/5 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-slate-200/50 flex items-center justify-center text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                        </div>
                        <div class="flex-1 mt-1">
                            <h5 class="text-slate-400 text-[10px] font-black uppercase tracking-widest leading-none mb-2">Additional Remarks / Notes</h5>
                            <p class="text-slate-800 font-bold leading-relaxed" x-text="selectedCheque?.remarks || 'No additional remarks provided for this cheque entry.'"></p>
                        </div>
                    </div>

                </div>

                <!-- Footer / Actions -->
                <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-100 flex justify-end items-center gap-4">
                    <button @click="viewModal = false"
                            class="px-8 py-3 bg-white text-slate-700 rounded-2xl font-black uppercase text-xs tracking-widest border border-slate-200 hover:bg-slate-50 transition active:scale-95">
                        Close Detailed View
                    </button>
                    <!-- Potential extra actions could go here, like Print -->
                </div>
            </div>
        </div>

    </main>
</div>

@livewireScripts

<script>
    function confirmDelete(event, form) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

</body>
</html>
