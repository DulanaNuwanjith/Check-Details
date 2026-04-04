<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Title & Description -->
        <div class="lg:col-span-1">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-key text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ __('Two Factor Authentication') }}</h3>
            <p class="mt-3 text-slate-400 text-sm font-semibold leading-relaxed">
                {{ __('Add an additional layer of security to your account using industry-standard two-factor authentication.') }}
            </p>
        </div>

        <div class="lg:col-span-2">
            <div class="space-y-6">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">
                    @if ($this->enabled)
                        @if ($showingConfirmation)
                            <span class="text-amber-500"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ __('Finish enabling two factor authentication.') }}</span>
                        @else
                            <span class="text-emerald-600"><i class="fa-solid fa-circle-check mr-2"></i>{{ __('You have enabled two factor authentication.') }}</span>
                        @endif
                    @else
                        <span class="text-slate-400"><i class="fa-solid fa-circle-info mr-2 text-slate-300"></i>{{ __('You have not enabled two factor authentication.') }}</span>
                    @endif
                </h3>

                <p class="text-slate-400 text-sm font-semibold leading-relaxed">
                    {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                </p>

                @if ($this->enabled)
                    @if ($showingQrCode)
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-6">
                            <p class="text-slate-800 font-bold text-sm">
                                @if ($showingConfirmation)
                                    {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                                @else
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                                @endif
                            </p>

                            <div class="p-4 inline-block bg-white rounded-2xl shadow-sm border border-slate-100">
                                {!! $this->user->twoFactorQrCodeSvg() !!}
                            </div>

                            <p class="text-slate-800 font-bold text-sm">
                                {{ __('Setup Key') }}: <span class="bg-white px-3 py-1 rounded-lg border border-slate-100 font-mono text-blue-600">{{ decrypt($this->user->two_factor_secret) }}</span>
                            </p>

                            @if ($showingConfirmation)
                                <div class="space-y-2 mt-6">
                                    <label for="code" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('Confirmation Code') }}</label>
                                    <input id="code" type="text" name="code" 
                                           class="w-full max-w-xs px-5 py-4 bg-white border border-slate-200 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:border-blue-600 focus:ring-0 transition"
                                           inputmode="numeric" autofocus autocomplete="one-time-code"
                                           wire:model="code"
                                           wire:keydown.enter="confirmTwoFactorAuthentication" />
                                    <x-input-error for="code" class="mt-2" />
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($showingRecoveryCodes)
                        <div class="p-6 bg-slate-900 rounded-3xl space-y-4">
                            <p class="text-slate-200 font-bold text-sm">
                                <i class="fa-solid fa-file-shield mr-2 text-blue-400"></i>{{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor device is lost.') }}
                            </p>

                            <div class="grid gap-2 grid-cols-2 font-mono text-xs text-blue-300 bg-slate-800/50 p-4 rounded-2xl">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <div class="flex flex-wrap gap-4 mt-8">
                    @if (! $this->enabled)
                        <x-confirms-password wire:then="enableTwoFactorAuthentication">
                            <button type="button" class="px-8 py-3.5 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition active:scale-95" wire:loading.attr="disabled">
                                {{ __('Enable 2FA Protection') }}
                            </button>
                        </x-confirms-password>
                    @else
                        @if ($showingRecoveryCodes)
                            <x-confirms-password wire:then="regenerateRecoveryCodes">
                                <button class="px-6 py-3 bg-slate-100 text-slate-800 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition">
                                    {{ __('Regenerate Codes') }}
                                </button>
                            </x-confirms-password>
                        @elseif ($showingConfirmation)
                            <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                                <button type="button" class="px-8 py-3.5 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-95" wire:loading.attr="disabled">
                                    {{ __('Confirm Enable') }}
                                </button>
                            </x-confirms-password>
                        @else
                            <x-confirms-password wire:then="showRecoveryCodes">
                                <button class="px-6 py-3 bg-slate-100 text-slate-800 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition">
                                    {{ __('Show Recovery Codes') }}
                                </button>
                            </x-confirms-password>
                        @endif

                        @if ($showingConfirmation)
                            <x-confirms-password wire:then="disableTwoFactorAuthentication">
                                <button class="px-6 py-3 bg-slate-50 text-slate-400 rounded-xl font-black text-xs uppercase tracking-widest hover:text-rose-500 transition" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </button>
                            </x-confirms-password>
                        @else
                            <x-confirms-password wire:then="disableTwoFactorAuthentication">
                                <button class="px-6 py-3 bg-rose-50 text-rose-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-100 transition" wire:loading.attr="disabled">
                                    {{ __('Disable 2FA') }}
                                </button>
                            </x-confirms-password>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
