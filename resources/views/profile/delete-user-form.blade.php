<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-rose-100/50 hover:shadow-xl hover:shadow-rose-500/5 transition duration-500">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Title & Description -->
        <div class="lg:col-span-1">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 mb-6 shadow-sm shadow-rose-100">
                <i class="fa-solid fa-user-slash text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ __('Dangerous Area') }}</h3>
            <p class="mt-3 text-slate-400 text-sm font-semibold leading-relaxed">
                {{ __('Permanently delete your account and all associated enterprise data. This action is irreversible and cannot be recovered.') }}
            </p>
        </div>

        <div class="lg:col-span-2">
            <div class="p-6 rounded-3xl bg-rose-50/50 border border-rose-100 space-y-4">
                <p class="text-rose-800 font-bold text-sm leading-relaxed">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently purged. Before proceeding, please ensure you have downloaded any critical data you wish to retain.') }}
                </p>

                <div class="pt-4">
                    <button wire:click="confirmUserDeletion" wire:loading.attr="disabled"
                            class="px-8 py-3.5 bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-200 hover:bg-rose-700 transition active:scale-95">
                        {{ __('Terminate My Account') }}
                    </button>
                </div>
            </div>

            <!-- Delete User Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmingUserDeletion">
                <x-slot name="title">
                    <span class="font-black text-rose-600 uppercase tracking-widest text-sm">{{ __('Final Confirmation Required') }}</span>
                </x-slot>

                <x-slot name="content">
                    <p class="text-slate-500 font-semibold leading-relaxed">
                        {{ __('Are you absolutely sure? This will permanently wipe your profile and access. Please enter your password to confirm identity verification.') }}
                    </p>

                    <div class="mt-6" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <input type="password" 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold focus:border-rose-600 focus:ring-0 transition"
                               placeholder="{{ __('Enter Administrative Password') }}"
                               x-ref="password"
                               wire:model="password"
                               wire:keydown.enter="deleteUser" />

                        <x-input-error for="password" class="mt-2" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-slate-50 text-slate-400 rounded-xl font-black text-xs uppercase tracking-widest hover:text-slate-600 transition">
                        {{ __('Abort') }}
                    </button>

                    <button class="ms-3 px-8 py-3 bg-rose-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-700 transition shadow-lg shadow-rose-100"
                                wire:click="deleteUser" 
                                wire:loading.attr="disabled">
                        {{ __('Terminate Everything') }}
                    </button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
