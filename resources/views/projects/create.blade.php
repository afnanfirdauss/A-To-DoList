<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="p-1 rounded-lg text-textsec hover:text-textmain hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Buat Proyek Baru</h1>
                <p class="text-xs text-textsec mt-0.5">Mulai inisiatif baru dan ajak rekan tim untuk berkolaborasi.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                        Nama Proyek <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           placeholder="Contoh: Redesign Aplikasi Mobile TaskFlow" 
                           class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('name') border-danger @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                        Deskripsi Singkat (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              placeholder="Tujuan, target pengerjaan, atau ruang lingkup proyek..." 
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('description') border-danger @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Accent -->
                <div>
                    <label class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-2">
                        Warna Aksen Proyek
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" id="color" value="{{ old('color', '#2F855A') }}" 
                               class="h-10 w-14 rounded-xl border-gray-200 cursor-pointer shadow-xs p-1">
                        <span class="text-xs text-textsec">Pilih warna untuk mempermudah identifikasi proyek di dashboard & kalender.</span>
                    </div>
                    @error('color')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-textsec hover:bg-gray-50 text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-semibold shadow-xs transition">
                        Simpan Proyek
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
