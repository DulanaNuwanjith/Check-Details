<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bank Account Management</title>

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
<div class="flex-1 flex flex-col h-screen overflow-hidden" x-data="{ openBankModal: false, bankForm: {} }">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 py-5 sticky top-0 z-10">
        <div>
            <h1 class="font-extrabold text-2xl text-gray-800">Bank Account Management</h1>
            <p class="text-sm text-gray-500 font-medium">Manage your bank accounts and their statuses</p>
        </div>

        <button @click="bankForm = {}; openBankModal = true"
                class="bg-green-600 text-white px-5 py-2.5 rounded-xl font-semibold shadow hover:bg-green-700 transition">
            + Add New Bank Account
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
                        <th class="px-6 py-4">Bank Name</th>
                        <th class="px-6 py-4">Branch</th>
                        <th class="px-6 py-4">Account Number</th>
                        <th class="px-6 py-4">Account Type</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                    @forelse($bankAccounts as $account)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-700">{{ $account->bank_name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $account->branch_name ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $account->account_number }}</td>
                            <td class="px-6 py-4">{{ ucfirst($account->account_type) }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($account->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Active</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-3">

                                <!-- Edit Button -->
                                <button
                                    @click="bankForm = @json($account); openBankModal = true"
                                    class="bg-blue-500 text-white px-4 py-1.5 rounded-full text-sm font-medium shadow hover:bg-blue-600 transition duration-200">
                                    Edit
                                </button>

                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('bank-accounts.destroy', $account->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-medium shadow hover:bg-red-600 transition duration-200">
                                        Delete
                                    </button>
                                </form>

                                <!-- Toggle Status Button -->
                                <form method="POST" action="{{ route('bank-accounts.toggle-status', $account->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="{{ $account->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}
                   text-white px-4 py-1.5 rounded-full text-sm font-medium shadow transition duration-200">
                                        {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">No bank accounts found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="openBankModal"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div @click.away="openBankModal = false"
                 class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-xl">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold" x-text="bankForm.id ? 'Edit Bank Account' : 'Add New Bank Account'">Add New Bank Account</h3>
                    <button @click="openBankModal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form :action="bankForm.id ? `/bank-accounts/${bankForm.id}` : '{{ route('bank-accounts.store') }}' "
                      method="POST">
                    @csrf
                    <template x-if="bankForm.id">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="bank_name" placeholder="Bank Name"
                               :value="bankForm.bank_name" required class="p-2 border rounded-lg">

                        <input type="text" name="branch_name" placeholder="Branch Name"
                               :value="bankForm.branch_name" class="p-2 border rounded-lg">

                        <input type="text" name="account_name" placeholder="Account Holder Name"
                               :value="bankForm.account_name" required class="p-2 border rounded-lg">

                        <input type="text" name="account_number" placeholder="Account Number"
                               :value="bankForm.account_number" required class="p-2 border rounded-lg">

                        <select name="account_type" class="p-2 border rounded-lg" :value="bankForm.account_type">
                            <option value="current">Current</option>
                            <option value="savings">Savings</option>
                            <option value="business">Business</option>
                        </select>

                        <select name="is_active" class="p-2 border rounded-lg" :value="bankForm.is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        <textarea name="remarks" placeholder="Remarks"
                                  class="col-span-2 p-2 border rounded-lg" :value="bankForm.remarks"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="openBankModal = false"
                                class="px-4 py-2 border rounded-lg">Cancel</button>

                        <button type="submit"
                                class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                            <span x-text="bankForm.id ? 'Update Account' : 'Add Account'">Add Account</span>
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
