<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-textmain">Buat Akun Baru</h2>
        <p class="text-xs text-textsec mt-1">Daftar sekarang untuk mulai mengorganisasi tugas dan produktivitas Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-textsec uppercase tracking-wider mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('name') border-danger @enderror">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-textsec uppercase tracking-wider mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('email') border-danger @enderror">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-textsec uppercase tracking-wider mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('password') border-danger @enderror">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-textsec uppercase tracking-wider mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('password_confirmation') border-danger @enderror">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div>
            <button type="submit" class="w-full py-2.5 px-4 bg-primary hover:bg-primary-600 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                Daftar Akun TaskFlow
            </button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-100 text-center text-xs text-textsec">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-bold text-primary hover:underline ml-1">
            Masuk
        </a>
    </div>
</x-guest-layout>
