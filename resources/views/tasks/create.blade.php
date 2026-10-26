<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tasks.index') }}" class="p-1 rounded-lg text-textsec hover:text-textmain hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Buat Tugas Baru</h1>
                <p class="text-xs text-textsec mt-0.5">Lengkapi form berikut untuk menambahkan tugas ke TaskFlow.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 sm:p-8">
            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                        Judul Tugas <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                           placeholder="Contoh: Implementasi endpoint API kalender" 
                           class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('title') border-danger @enderror">
                    @error('title')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                        Deskripsi Tugas (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              placeholder="Rincian, catatan, atau kriteria penyelesaian tugas..." 
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('description') border-danger @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project & Category Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="project_id" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Proyek
                        </label>
                        <select name="project_id" id="project_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">-- Tugas Pribadi (Tanpa Proyek) --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ (old('project_id', $preselectedProjectId) == $project->id) ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Kategori
                        </label>
                        <select name="category_id" id="category_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status, Priority & Assignee Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="todo" {{ old('status', 'todo') === 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Prioritas <span class="text-danger">*</span>
                        </label>
                        <select name="priority" id="priority" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Penanggung Jawab
                        </label>
                        <select name="assigned_to" id="assigned_to" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">-- Belum Ditugaskan --</option>
                            @foreach($assignees as $assignee)
                                <option value="{{ $assignee->id }}" {{ old('assigned_to') == $assignee->id ? 'selected' : '' }}>
                                    {{ $assignee->name }} {{ $assignee->id === Auth::id() ? '(Saya)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dates Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Tanggal Mulai (Opsional)
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" 
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('start_date') border-danger @enderror">
                        @error('start_date')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-xs font-semibold text-textmain uppercase tracking-wider mb-1">
                            Tenggat Waktu / Deadline (Opsional)
                        </label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs @error('due_date') border-danger @enderror">
                        @error('due_date')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-textsec hover:bg-gray-50 text-sm font-semibold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white text-sm font-semibold shadow-xs transition">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
