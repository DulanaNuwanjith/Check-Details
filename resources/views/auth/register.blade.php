<x-guest-layout>
    <x-authentication-card>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Create an Account</h2>
            <p class="text-gray-500 mt-1 text-sm">Join us to start managing your check operations securely</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" onsubmit="return validateForm()" enctype="multipart/form-data">
            @csrf

            <!-- First Name -->
            <div>
                <x-label for="first_name" value="First Name" />
                <x-input id="first_name" class="block mt-1 w-full"
                         type="text"
                         name="first_name"
                         :value="old('first_name')"
                         required
                         pattern="[A-Za-z\s]{2,50}" />
                <p id="firstNameError" class="text-sm text-red-500 mt-1 hidden">
                    Only letters allowed (2–50 characters)
                </p>
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-label for="last_name" value="Last Name" />
                <x-input id="last_name" class="block mt-1 w-full"
                         type="text"
                         name="last_name"
                         :value="old('last_name')"
                         required
                         pattern="[A-Za-z\s]{2,50}" />
                <p id="lastNameError" class="text-sm text-red-500 mt-1 hidden">
                    Only letters allowed (2–50 characters)
                </p>
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full"
                         type="email"
                         name="email"
                         :value="old('email')"
                         required />
                <p id="emailError" class="text-sm text-red-500 mt-1 hidden">
                    Enter a valid email address
                </p>
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

                <p id="nicError" class="text-sm text-red-500 mt-1 hidden">
                    Invalid NIC format. Use 9 digits + V or 12 digits.
                </p>
            </div>

            <!-- Role -->
            <div class="mt-4">
                <x-label for="role" value="Role" />
                <select name="role" id="role"
                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                        required>
                    <option value="">Select Role</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <p id="roleError" class="text-sm text-red-500 mt-1 hidden">
                    Please select a role
                </p>
            </div>

            <!-- Profile Photo -->
            <div class="mt-4">
                <x-label for="photo" value="Profile Photo (Optional)" />
                <input id="photo" class="block mt-1 w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
                    type="file" name="photo" accept="image/jpeg,image/png,image/jpg" />
                <p class="text-xs text-gray-500 mt-1">
                    Max size: 1MB. Format: JPG, PNG
                </p>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full"
                         type="password"
                         name="password"
                         required />

                <p id="passwordError" class="text-sm text-red-500 mt-1 hidden">
                    Password must be at least 8 characters
                </p>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="Confirm Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full"
                         type="password"
                         name="password_confirmation"
                         required />

                <p id="confirmPasswordError" class="text-sm text-red-500 mt-1 hidden">
                    Passwords do not match
                </p>
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

            <div class="flex flex-col sm:flex-row items-center justify-between mt-8 mb-4">
                <a class="underline text-sm text-gray-600 hover:text-blue-600 mb-4 sm:mb-0" href="{{ route('login') }}">
                    Already registered?
                </a>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Register
            </button>
        </form>

        <!-- VALIDATION SCRIPT -->
        <script>
            const oldNIC = /^[0-9]{9}[vVxX]$/;
            const newNIC = /^[0-9]{12}$/;

            function setError(input, errorEl, condition) {
                if (condition) {
                    errorEl.classList.remove('hidden');
                    input.classList.add('border-red-500');
                    return false;
                } else {
                    errorEl.classList.add('hidden');
                    input.classList.remove('border-red-500');
                    return true;
                }
            }

            function validateForm() {
                let valid = true;

                valid &= validateFirstName();
                valid &= validateLastName();
                valid &= validateEmail();
                valid &= validateNIC();
                valid &= validateRole();
                valid &= validatePassword();
                valid &= validateConfirmPassword();

                return !!valid;
            }

            function validateFirstName() {
                const input = document.getElementById('first_name');
                const error = document.getElementById('firstNameError');
                return setError(input, error, !/^[A-Za-z\s]{2,50}$/.test(input.value));
            }

            function validateLastName() {
                const input = document.getElementById('last_name');
                const error = document.getElementById('lastNameError');
                return setError(input, error, !/^[A-Za-z\s]{2,50}$/.test(input.value));
            }

            function validateEmail() {
                const input = document.getElementById('email');
                const error = document.getElementById('emailError');
                return setError(input, error, !/^\S+@\S+\.\S+$/.test(input.value));
            }

            function validateNIC() {
                const input = document.getElementById('nic');
                const error = document.getElementById('nicError');
                return setError(input, error, !(oldNIC.test(input.value) || newNIC.test(input.value)));
            }

            function validateRole() {
                const input = document.getElementById('role');
                const error = document.getElementById('roleError');
                return setError(input, error, input.value === "");
            }

            function validatePassword() {
                const input = document.getElementById('password');
                const error = document.getElementById('passwordError');
                return setError(input, error, input.value.length < 8);
            }

            function validateConfirmPassword() {
                const input = document.getElementById('password_confirmation');
                const error = document.getElementById('confirmPasswordError');
                const password = document.getElementById('password').value;

                return setError(input, error, input.value !== password);
            }

            // 🔥 REAL-TIME VALIDATION
            document.getElementById('first_name').addEventListener('input', validateFirstName);
            document.getElementById('last_name').addEventListener('input', validateLastName);
            document.getElementById('email').addEventListener('input', validateEmail);
            document.getElementById('nic').addEventListener('input', validateNIC);
            document.getElementById('role').addEventListener('change', validateRole);
            document.getElementById('password').addEventListener('input', validatePassword);
            document.getElementById('password_confirmation').addEventListener('input', validateConfirmPassword);
        </script>

    </x-authentication-card>
</x-guest-layout>
