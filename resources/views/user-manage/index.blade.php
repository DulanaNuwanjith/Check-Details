<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Outfit:400,500,600,700,800&display=swap" rel="stylesheet"/>

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

<x-sidebar/>

<div class="flex-1 flex flex-col h-screen overflow-hidden"
     x-data="{ openUserModal: false, userForm: {} }">

    <!-- HEADER -->
    <header class="bg-white/80 backdrop-blur-md border-b px-8 py-5 flex justify-between">
        <div>
            <h1 class="text-2xl font-extrabold">User Management</h1>
            <p class="text-sm text-gray-500">Manage application users and permissions</p>
        </div>

        <button @click="
                            userForm = {
                                first_name: '',
                                last_name: '',
                                email: '',
                                nic: '',
                                role: 'user',
                                password: '',
                                password_confirmation: ''
                            };
                            openUserModal = true;
                        "
                class="bg-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-green-700">
            + Add User
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
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">NIC</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 font-medium">{{ $user->nic }}</td>

                            <!-- ROLE -->
                            <td class="px-6 py-4 text-center">
                                @if($user->role === 'superadmin')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">SuperAdmin</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">User</span>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 flex justify-center gap-2">

                                <!-- EDIT -->
                                <button
                                    @click="
                                                userForm = {
                                                    id: {{ $user->id }},
                                                    first_name: '{{ addslashes($user->first_name) }}',
                                                    last_name: '{{ addslashes($user->last_name) }}',
                                                    email: '{{ addslashes($user->email) }}',
                                                    nic: '{{ addslashes($user->nic) }}',
                                                    role: '{{ $user->role }}',
                                                    password: '',
                                                    password_confirmation: ''
                                                };
                                                openUserModal = true;
                                            "
                                    class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm hover:bg-blue-600">
                                    Edit
                                </button>

                                <!-- DELETE -->
                                @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                          onsubmit="confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1 rounded-full text-sm hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>

        <!-- MODAL -->
        <div x-show="openUserModal"
             x-cloak
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

            <div @click.away="openUserModal = false"
                 class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-xl">

                <div class="flex justify-between mb-4 border-b pb-4">
                    <h3 class="text-xl font-bold text-gray-800"
                        x-text="userForm.id ? 'Edit User' : 'Add User'"></h3>
                    <button @click="openUserModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- FORM -->
                <form :action="userForm.id
                ? `/users/${userForm.id}`
                : '{{ route('users.store') }}'"
                      method="POST">
                    @csrf
                    <template x-if="userForm.id">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-2 gap-4">

                        <!-- First Name -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name"
                                   placeholder="First Name"
                                   x-model="userForm.first_name"
                                   required class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Last Name -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name"
                                   placeholder="Last Name"
                                   x-model="userForm.last_name"
                                   required class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col col-span-2">
                            <label class="mb-1 text-gray-700 font-medium">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email"
                                   placeholder="email@example.com"
                                   x-model="userForm.email"
                                   required class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- NIC -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                NIC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nic"
                                   placeholder="NIC Number"
                                   x-model="userForm.nic"
                                   required class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Role -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role"
                                    class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                                    x-model="userForm.role">
                                <option value="user">User</option>
                                <option value="superadmin">SuperAdmin</option>
                            </select>
                        </div>

                        <!-- Password -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                Password <span class="text-red-500" x-show="!userForm.id">*</span>
                                <span class="text-[10px] text-gray-400 font-normal" x-show="userForm.id">(Leave blank to keep current)</span>
                            </label>
                            <input type="password" name="password"
                                   placeholder="Password"
                                   x-model="userForm.password"
                                   :required="!userForm.id"
                                   class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Confirm Password -->
                        <div class="flex flex-col">
                            <label class="mb-1 text-gray-700 font-medium">
                                Confirm Password <span class="text-red-500" x-show="!userForm.id">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                   placeholder="Confirm Password"
                                   x-model="userForm.password_confirmation"
                                   :required="!userForm.id"
                                   class="p-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                        <button type="button"
                                @click="openUserModal = false"
                                class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>

                        <button type="submit"
                                class="bg-green-600 text-white px-8 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <span x-text="userForm.id ? 'Update User' : 'Create User'"></span>
                        </button>
                    </div>
                </form>
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
            text: "This action will permanently delete the user account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete user!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
</body>
</html>
