<div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Title & Description -->
        <div class="lg:col-span-1">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-6 shadow-sm shadow-blue-100">
                <i class="fa-solid fa-id-card-clip text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ __('Profile Information') }}</h3>
            <p class="mt-3 text-slate-400 text-sm font-semibold leading-relaxed">
                {{ __('Keep your personal identity updated. Your name and contact details are used across the enterprise system.') }}
            </p>
        </div>

        <!-- Form Area -->
        <div class="lg:col-span-2">
            <form wire:submit="updateProfileInformation" class="space-y-8">
                <!-- Profile Photo -->
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div x-data="{photoName: null, photoPreview: null}" class="relative">
                        <input type="file" id="photo" class="hidden"
                                    wire:model.live="photo"
                                    x-ref="photo"
                                    x-on:change="
                                            photoName = $refs.photo.files[0].name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                photoPreview = e.target.result;
                                            };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                    " />

                        <div class="flex items-center gap-6">
                            <!-- Avatar Display -->
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-sm" x-show="! photoPreview">
                                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="w-24 h-24 rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-sm" x-show="photoPreview" style="display: none;">
                                    <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                    </span>
                                </div>
                                <button type="button" x-on:click.prevent="$refs.photo.click()" 
                                        class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-600 text-white rounded-xl shadow-lg border-2 border-white flex items-center justify-center hover:bg-blue-700 transition active:scale-90">
                                    <i class="fa-solid fa-camera text-xs"></i>
                                </button>
                            </div>
                            
                            <div>
                                <h4 class="text-slate-800 font-black text-sm uppercase tracking-widest mb-1">Avatar Image</h4>
                                <p class="text-slate-400 text-xs font-semibold">Recommended 800x800px high quality</p>
                                @if ($this->user->profile_photo_path)
                                    <button type="button" class="mt-2 text-rose-500 text-[10px] font-black uppercase tracking-widest hover:text-rose-600 transition" wire:click="deleteProfilePhoto">
                                        Remove Photo
                                    </button>
                                @endif
                            </div>
                        </div>

                        <x-input-error for="photo" class="mt-2" />
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('Full Display Name') }}</label>
                        <input id="name" type="text" 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:bg-white focus:border-blue-600 focus:ring-0 transition"
                               wire:model="state.name" required autocomplete="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">{{ __('Email Address') }}</label>
                        <input id="email" type="email" 
                               class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-800 font-bold placeholder-slate-300 focus:bg-white focus:border-blue-600 focus:ring-0 transition"
                               wire:model="state.email" required autocomplete="username" />
                        <x-input-error for="email" class="mt-2" />

                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                            <p class="text-xs mt-2 font-bold text-amber-600 italic">
                                {{ __('Your email address is unverified.') }}
                                <button type="button" class="underline hover:text-amber-700 ml-1" wire:click.prevent="sendEmailVerification">
                                    {{ __('Resend verification link.') }}
                                </button>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-4 mt-10">
                    <x-action-message class="text-emerald-600 font-black text-xs uppercase tracking-widest" on="saved">
                         <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Changes Saved Successfully.') }}
                    </x-action-message>

                    <button wire:loading.attr="disabled" wire:target="photo"
                            class="px-8 py-3.5 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-95 disabled:opacity-50">
                        {{ __('Save Information') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
