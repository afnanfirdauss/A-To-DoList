<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textmain">Selamat Datang Kembali</h2>
        <p class="text-xs text-textsec mt-1">Masuk untuk mengelola tugas dan proyek Anda di TaskFlow.</p>
    </div>

    <!-- Demo Credentials Helper -->
    <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200/80 rounded-xl text-xs text-emerald-900 space-y-1">
        <div class="flex items-center gap-1.5 font-bold text-emerald-800">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Akun Demo Portofolio:
        </div>
        <p>• <strong>Owner:</strong> <code class="bg-white/80 px-1 py-0.5 rounded text-[11px]">demo@taskflow.test</code> | Password: <code class="bg-white/80 px-1 py-0.5 rounded text-[11px]">password</code></p>
        <p>• <strong>Member:</strong> <code class="bg-white/80 px-1 py-0.5 rounded text-[11px]">member@taskflow.test</code> | Password: <code class="bg-white/80 px-1 py-0.5 rounded text-[11px]">password</code></p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-textsec uppercase tracking-wider mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', 'demo@taskflow.test') }}" required autofocus autocomplete="username"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('email') border-danger @enderror">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-semibold text-textsec uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-primary hover:underline font-medium" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" value="password" required autocomplete="current-password"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('password') border-danger @enderror">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-xs focus:ring-primary" name="remember">
                <span class="ms-2 text-xs text-textsec">Ingat saya</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-600 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                Masuk ke TaskFlow
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-textsec">
        Belum memiliki akun?
        <a href="{{ route('register') }}" class="font-bold text-primary hover:underline ml-1">
            Daftar Sekarang
        </a>
    </div>
</x-guest-layout>
