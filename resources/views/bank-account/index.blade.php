<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bank Account Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="text-gray-900 bg-gray-50 flex h-screen overflow-hidden antialiased">

<x-sidebar />

<div class="flex-1 flex flex-col h-screen overflow-hidden"
     x-data="{ openBankModal: false, bankForm: {} }">

    <!-- HEADER -->
    <header class="bg-white/80 backdrop-blur-md border-b px-8 py-5 flex justify-between">
        <div>
            <h1 class="text-2xl font-extrabold">Bank Account Management</h1>
            <p class="text-sm text-gray-500">Manage your bank accounts</p>
        </div>

        <button @click="bankForm = {}; openBankModal = true"
                class="bg-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-green-700">
            + Add Bank
        </button>
    </header>

    <!-- CONTENT -->
    <main class="flex-1 overflow-y-auto p-8">

        <!-- TABLE -->
        <div class="bg-white rounded-2xl shadow border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Bank Name</th>
                        <th class="px-6 py-4">Branch</th>
                        <th class="px-6 py-4">Bank Code</th>
                        <th class="px-6 py-4">Company Name</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                    @forelse($bankAccounts as $account)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $account->bank_name }}</td>
                            <td class="px-6 py-4">{{ $account->branch_name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $account->bank_code ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium">{{ $account->company_name }}</td>

                            <!-- STATUS -->
                            <td class="px-6 py-4 text-center">
                                @if($account->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Active</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Inactive</span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 flex justify-center gap-2">

                                <!-- EDIT -->
                                <button
                                    @click="bankForm = @json($account); openBankModal = true"
                                    class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm hover:bg-blue-600">
                                    Edit
                                </button>

                                <!-- DELETE -->
                                <form method="POST" action="{{ route('bank-accounts.destroy', $account->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white px-3 py-1 rounded-full text-sm hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>

                                <!-- TOGGLE -->
                                <form method="POST" action="{{ route('bank-accounts.toggle-status', $account->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-white px-3 py-1 rounded-full text-sm
                                        {{ $account->is_active ? 'bg-yellow-500' : 'bg-green-500' }}">
                                        {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">
                                No bank accounts found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="openBankModal"
             x-cloak
             class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div @click.away="openBankModal = false"
                 class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-xl">

                <div class="flex justify-between mb-4">
                    <h3 class="text-xl font-bold"
                        x-text="bankForm.id ? 'Edit Bank' : 'Add Bank'"></h3>
                    <button @click="openBankModal = false">✕</button>
                </div>

                <!-- FORM -->
                <form :action="bankForm.id ? `/bank-accounts/${bankForm.id}` : '{{ route('bank-accounts.store') }}'"
                      method="POST">
                    @csrf
                    <template x-if="bankForm.id">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-2 gap-4">

                        <input type="text" name="bank_name"
                               placeholder="Bank Name"
                               :value="bankForm.bank_name"
                               required class="p-2 border rounded-lg">

                        <input type="text" name="branch_name"
                               placeholder="Branch Name"
                               :value="bankForm.branch_name"
                               class="p-2 border rounded-lg">

                        <input type="text" name="bank_code"
                               placeholder="Bank Code"
                               :value="bankForm.bank_code"
                               class="p-2 border rounded-lg">

                        <input type="text" name="company_name"
                               placeholder="Company Name"
                               :value="bankForm.company_name"
                               required class="p-2 border rounded-lg">

                        <select name="is_active"
                                class="p-2 border rounded-lg col-span-2"
                                :value="bankForm.is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        <textarea name="remarks"
                                  placeholder="Remarks"
                                  class="col-span-2 p-2 border rounded-lg"
                                  x-text="bankForm.remarks"></textarea>

                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button"
                                @click="openBankModal = false"
                                class="px-4 py-2 border rounded-lg">
                            Cancel
                        </button>

                        <button type="submit"
                                class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                            <span x-text="bankForm.id ? 'Update' : 'Create'"></span>
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
