<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" onsubmit="return validateForm()">
            @csrf

            <!-- First Name -->
            <div>
                <x-label for="first_name" value="First Name" />
                <x-input id="first_name" class="block mt-1 w-full"
                         type="text"
                         name="first_name"
                         :value="old('first_name')"
                         required
                         pattern="[A-Za-z\s]{2,50}"
                         title="Only letters allowed (2-50 characters)" />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-label for="last_name" value="Last Name" />
                <x-input id="last_name" class="block mt-1 w-full"
                         type="text"
                         name="last_name"
                         :value="old('last_name')"
                         required
                         pattern="[A-Za-z\s]{2,50}"
                         title="Only letters allowed (2-50 characters)" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full"
                         type="email"
                         name="email"
                         :value="old('email')"
                         required />
            </div>

            <!-- NIC -->
            <div class="mt-4">
                <x-label for="nic" value="NIC Number" />
                <x-input id="nic" class="block mt-1 w-full"
                         type="text"
                         name="nic"
                         :value="old('nic')"
                         required
                         placeholder="e.g. 200012345678 or 901234567V" />
                <p class="text-sm text-gray-500 mt-1">Enter valid Sri Lankan NIC</p>
            </div>

            <!-- Role -->
            <div class="mt-4">
                <x-label for="role" value="Role" />
                <select name="role" id="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirm Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full"
                         type="password"
                         name="password_confirmation"
                         required />
            </div>

            <!-- Terms -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2 text-sm">
                                I agree to Terms & Privacy Policy
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600" href="{{ route('login') }}">
                    Already registered?
                </a>

                <x-button class="ms-4">
                    Register
                </x-button>
            </div>
        </form>

        <!-- JS Validation -->
        <script>
            function validateForm() {
                const nic = document.getElementById('nic').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                // NIC validation (Sri Lankan)
                const oldNIC = /^[0-9]{9}[vVxX]$/;
                const newNIC = /^[0-9]{12}$/;

                if (!(oldNIC.test(nic) || newNIC.test(nic))) {
                    alert("Invalid NIC format. Use 9 digits + V or 12 digits.");
                    return false;
                }

                // Password validation
                if (password.length < 8) {
                    alert("Password must be at least 8 characters long.");
                    return false;
                }

                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    return false;
                }

                return true;
            }
        </script>

    </x-authentication-card>
</x-guest-layout>
