<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Title & Description -->
        <div class="lg:col-span-1">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 mb-6 shadow-sm shadow-amber-100">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ __('Security & Password') }}</h3>
            <p class="mt-3 text-slate-400 text-sm font-semibold leading-relaxed">
                {{ __('Ensure your account is using a long, random password to stay secure against unauthorized access.') }}
            </p>
        </div>

        <!-- Form Area -->
        <div class="lg:col-span-2">
            <form wire:submit="updatePassword" class="space-y-8">
                
                <div class="space-y-8">
                    <!-- Current Password -->
                    <div class="space-y-2">
                        <label for="current_password" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('Current Password') }}</label>
                        <input id="current_password" type="password" 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:bg-white focus:border-blue-600 focus:ring-0 transition"
                               wire:model="state.current_password" autocomplete="current-password" />
                        <x-input-error for="current_password" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- New Password -->
                        <div class="space-y-2">
                            <label for="password" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('New Password') }}</label>
                            <input id="password" type="password" 
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:bg-white focus:border-blue-600 focus:ring-0 transition"
                                   wire:model="state.password" autocomplete="new-password" />
                            <x-input-error for="password" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('Confirm New Password') }}</label>
                            <input id="password_confirmation" type="password" 
                                   class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:bg-white focus:border-blue-600 focus:ring-0 transition"
                                   wire:model="state.password_confirmation" autocomplete="new-password" />
                            <x-input-error for="password_confirmation" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-4 mt-10">
                    <x-action-message class="text-emerald-600 font-black text-xs uppercase tracking-widest" on="saved">
                         <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Password Updated Successfully.') }}
                    </x-action-message>

                    <button class="px-8 py-3.5 bg-slate-800 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-slate-200 hover:bg-slate-900 transition active:scale-95">
                        {{ __('Update Security') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
