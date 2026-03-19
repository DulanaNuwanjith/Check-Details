<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 sm:p-8 font-sans antialiased text-gray-900">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl flex flex-col md:flex-row overflow-hidden relative">
        
        <!-- Left Panel: Branding / Imagery -->
        <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-blue-600 to-blue-900 p-10 text-white flex-col justify-center items-center relative z-10">
            <!-- Decorative shapes -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>

            <div class="text-center z-20">
                <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/20 inline-flex items-center justify-center mb-6 shadow-lg">
                    <!-- Updated to use the requested logo logo in storage public -->
                    <img src="{{ asset('storage/main_logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=Rangiri+Holdings&color=ffffff&background=2563eb'" alt="Rangiri Holdings" class="h-24 w-auto object-contain drop-shadow-md">
                </div>
                <h1 class="text-3xl font-bold tracking-tight mb-2 drop-shadow-md">Rangiri Holdings</h1>
                <h2 class="text-sm font-semibold text-blue-200 tracking-widest uppercase mb-6 drop-shadow">Check Management System</h2>
                <div class="w-12 h-1 bg-blue-400 mx-auto rounded-full mb-6"></div>
                <p class="text-blue-100 text-base leading-relaxed font-light px-2">
                    Streamlining your check operations with top-tier efficiency and precision.
                </p>
            </div>
        </div>

        <!-- Right Panel: Auth Form -->
        <div class="w-full md:w-7/12 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white z-10 relative">
            <!-- Mobile header -->
            <div class="md:hidden text-center mb-8">
                <img src="{{ asset('storage/main_logo.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=RH&color=ffffff&background=2563eb'" alt="Rangiri Holdings" class="h-16 mx-auto object-contain mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Rangiri Holdings</h1>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-widest mt-1">Check Management System</p>
            </div>
            
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
