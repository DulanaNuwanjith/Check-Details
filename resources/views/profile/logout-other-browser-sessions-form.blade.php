<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Title & Description -->
        <div class="lg:col-span-1">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-6 shadow-sm shadow-blue-100">
                <i class="fa-solid fa-laptop-code text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ __('Active Sessions') }}</h3>
            <p class="mt-3 text-slate-400 text-sm font-semibold leading-relaxed">
                {{ __('For your security, you may log out of all of your other browser sessions across all of your devices. If you feel compromised, update your password.') }}
            </p>
        </div>

        <div class="lg:col-span-2">
            <div class="space-y-6">
                @if (count($this->sessions) > 0)
                    <div class="space-y-4">
                        <!-- Other Browser Sessions -->
                        @foreach ($this->sessions as $session)
                            <div class="flex items-center p-5 rounded-2xl bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-md transition duration-300">
                                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition">
                                    @if ($session->agent->isDesktop())
                                        <i class="fa-solid fa-desktop text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-mobile-screen-button text-xl"></i>
                                    @endif
                                </div>

                                <div class="ms-4 flex-1">
                                    <div class="text-sm font-black text-slate-800 tracking-tight">
                                        {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown OS') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown Browser') }}
                                    </div>

                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        {{ $session->ip_address }} • 
                                        @if ($session->is_current_device)
                                            <span class="text-emerald-500">{{ __('Active Now') }}</span>
                                        @else
                                            {{ __('Last active') }} {{ $session->last_active }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center mt-8">
                    <button wire:click="confirmLogout" wire:loading.attr="disabled"
                            class="px-8 py-3.5 bg-slate-800 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-slate-200 hover:bg-slate-900 transition active:scale-95">
                        {{ __('Secure All Sessions') }}
                    </button>

                    <x-action-message class="ms-4 text-emerald-600 font-black text-xs uppercase tracking-widest" on="loggedOut">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Terminal Secured.') }}
                    </x-action-message>
                </div>

                <!-- Log Out Other Devices Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmingLogout">
                    <x-slot name="title">
                        <span class="font-black text-slate-800">{{ __('Confirm Security Reset') }}</span>
                    </x-slot>

                    <x-slot name="content">
                        <p class="text-slate-500 font-semibold">{{ __('Please enter your administrative password to confirm you would like to log out of your other browser sessions across all of your devices.') }}</p>

                        <div class="mt-6" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                            <input type="password" 
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:border-blue-600 focus:ring-0 transition"
                                   placeholder="{{ __('Confirm Password') }}"
                                   x-ref="password"
                                   wire:model="password"
                                   wire:keydown.enter="logoutOtherBrowserSessions" />

                            <x-input-error for="password" class="mt-2" />
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled"
                                class="px-6 py-3 bg-slate-50 text-slate-400 rounded-xl font-black text-xs uppercase tracking-widest hover:text-slate-600 transition">
                            {{ __('Abort') }}
                        </button>

                        <button class="ms-3 px-8 py-3 bg-blue-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-100"
                                    wire:click="logoutOtherBrowserSessions"
                                    wire:loading.attr="disabled">
                            {{ __('Logout Other Sessions') }}
                        </button>
                    </x-slot>
                </x-dialog-modal>
            </div>
        </div>
    </div>
</div>
