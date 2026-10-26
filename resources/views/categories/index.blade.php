<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Kategori Tugas</h1>
                <p class="text-xs text-textsec mt-0.5">Kelola kategori atau tag untuk mengelompokkan tugas pribadi dan tim Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Add Category Form -->
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6">
            <h2 class="text-sm font-bold text-textmain mb-3">Tambah Kategori Baru</h2>
            <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="name" required placeholder="Contoh: Frontend, Bugfix, Marketing, Riset..." 
                       class="flex-1 text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-xs font-semibold rounded-xl shadow-xs transition shrink-0">
                    Tambah Kategori
                </button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-textmain">Daftar Kategori Anda</h3>
            </div>

            @if($categories->isEmpty())
                <div class="py-12 text-center text-textsec text-xs">
                    Belum ada kategori yang dibuat. Gunakan form di atas untuk menambahkan kategori pertama Anda.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($categories as $category)
                        <div class="p-4 flex items-center justify-between gap-4" x-data="{ editing: false, name: '{{ $category->name }}' }">
                            <!-- View State -->
                            <div x-show="!editing" class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary/40"></span>
                                <span class="text-sm font-semibold text-textmain">#{{ $category->name }}</span>
                                <span class="text-xs text-textsec bg-gray-100 px-2.5 py-0.5 rounded-full">
                                    {{ $category->tasks_count }} Tugas
                                </span>
                            </div>

                            <!-- Edit Form Inline -->
                            <form x-show="editing" x-cloak method="POST" action="{{ route('categories.update', $category) }}" class="flex items-center gap-2 flex-1 max-w-md">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" x-model="name" required class="flex-1 text-xs py-1.5 px-3 rounded-lg border-gray-200">
                                <button type="submit" class="px-3 py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primary-600 transition">
                                    Simpan
                                </button>
                                <button type="button" @click="editing = false" class="px-3 py-1.5 border border-gray-200 text-textsec text-xs font-semibold rounded-lg hover:bg-gray-50 transition">
                                    Batal
                                </button>
                            </form>

                            <!-- Actions -->
                            <div x-show="!editing" class="flex items-center gap-2">
                                <button type="button" @click="editing = true" class="p-1.5 text-textsec hover:text-amber-600 rounded-lg transition" title="Edit Kategori">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini? Tugas terkait tidak akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-textsec hover:text-danger rounded-lg transition" title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
