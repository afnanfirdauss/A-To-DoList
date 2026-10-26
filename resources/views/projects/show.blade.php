<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="p-1 rounded-lg text-textsec hover:text-textmain hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-full inline-block" style="background-color: {{ $project->color ?? '#2F855A' }};"></span>
                        <h1 class="text-2xl font-bold tracking-tight text-textmain truncate max-w-xl">{{ $project->name }}</h1>
                    </div>
                    <p class="text-xs text-textsec mt-0.5">Pemilik: {{ $project->owner->name }} • Dibuat {{ $project->created_at->isoFormat('D MMMM Y') }}</p>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary hover:bg-primary-600 text-white text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Tugas Proyek
                </a>

                @if($isOwner)
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Proyek
                    </a>

                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini? Tugas terkait akan dipindahkan ke tugas pribadi.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-danger text-xs font-semibold transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Proyek
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Project Overview & Progress Card -->
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
            @if($project->description)
                <p class="text-sm text-textsec leading-relaxed">{{ $project->description }}</p>
            @endif

            <div class="space-y-2 pt-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-semibold text-textsec">Progres Penyelesaian Tugas</span>
                    <span class="font-bold text-textmain text-sm">{{ $progress }}% Selesai ({{ $tasks->where('status', 'done')->count() }} / {{ $tasks->count() }} Tugas)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500" 
                         style="width: {{ $progress }}%; background-color: {{ $project->color ?? '#2F855A' }};"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left 2 Cols: Project Tasks List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-textmain">Daftar Tugas Proyek</h2>
                            <span class="text-xs font-semibold text-textsec bg-gray-100 px-2 py-0.5 rounded-full">{{ $tasks->count() }}</span>
                        </div>

                        <!-- Filter in project -->
                        <form method="GET" action="{{ route('projects.show', $project) }}" class="flex items-center gap-2">
                            <select name="status" onchange="this.form.submit()" class="text-xs py-1 px-2.5 rounded-xl border-gray-200">
                                <option value="">Semua Status</option>
                                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <select name="priority" onchange="this.form.submit()" class="text-xs py-1 px-2.5 rounded-xl border-gray-200">
                                <option value="">Semua Prioritas</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </form>
                    </div>

                    @if($tasks->isEmpty())
                        <div class="py-12 text-center text-textsec text-sm">
                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Belum ada tugas di dalam proyek ini.
                            <div class="mt-3">
                                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-xs text-primary font-semibold hover:underline">
                                    + Tambah tugas sekarang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach($tasks as $task)
                                <div class="py-3 flex items-center justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-textmain hover:text-primary truncate block {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">
                                                {{ $task->title }}
                                            </a>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $task->priority === 'high' ? 'bg-red-50 text-danger' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-textsec mt-0.5">
                                            @if($task->assignee)
                                                <span>Ditugaskan ke: <strong class="text-textmain">{{ $task->assignee->name }}</strong></span>
                                            @else
                                                <span class="text-gray-400">Belum ditugaskan</span>
                                            @endif
                                            <span>•</span>
                                            <span class="{{ $task->isOverdue() ? 'text-danger font-bold' : ($task->isDueSoon() ? 'text-amber-600 font-semibold' : '') }}">
                                                {{ $task->due_date ? $task->due_date->format('d M Y') : 'Tanpa deadline' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($task->status === 'done')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Done</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">In Progress</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">To Do</span>
                                        @endif

                                        <a href="{{ route('tasks.show', $task) }}" class="p-1 text-textsec hover:text-primary rounded">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 1 Col: Team Members Management -->
            <div class="space-y-6">
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <h2 class="text-base font-bold text-textmain">Anggota Tim Proyek</h2>
                        <span class="text-xs font-semibold text-textsec bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $project->members->count() + 1 }}
                        </span>
                    </div>

                    <!-- Add Member Form (Owner only) -->
                    @if($isOwner)
                        <form method="POST" action="{{ route('projects.members.add', $project) }}" class="space-y-2 pb-4 border-b border-gray-100">
                            @csrf
                            <label for="member_email" class="block text-xs font-semibold text-textsec uppercase tracking-wider">
                                Tambah Anggota (Email)
                            </label>
                            <div class="flex gap-2">
                                <input type="email" name="email" id="member_email" required placeholder="user@taskflow.test" 
                                       class="flex-1 text-xs py-2 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                                <button type="submit" class="px-3 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-semibold rounded-xl shadow-xs transition shrink-0">
                                    Tambah
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- Members List -->
                    <div class="divide-y divide-gray-100">
                        <!-- Owner Card -->
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-primary text-white font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($project->owner->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-textmain">{{ $project->owner->name }}</p>
                                    <p class="text-[10px] text-textsec">{{ $project->owner->email }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary/10 text-primary">
                                Owner
                            </span>
                        </div>

                        <!-- Member List -->
                        @foreach($project->members as $member)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-textmain">{{ $member->name }}</p>
                                        <p class="text-[10px] text-textsec">{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">
                                        Member
                                    </span>

                                    @if($isOwner)
                                        <form method="POST" action="{{ route('projects.members.remove', [$project, $member]) }}" onsubmit="return confirm('Hapus anggota ini dari proyek?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-danger rounded transition" title="Keluarkan Anggota">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
