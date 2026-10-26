<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}" class="p-1 rounded-lg text-textsec hover:text-textmain hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-textmain truncate max-w-xl">{{ $task->title }}</h1>
                    <div class="flex items-center gap-2 text-xs text-textsec mt-0.5">
                        <span>Dibuat oleh {{ $task->creator->name }}</span>
                        <span>•</span>
                        <span>{{ $task->created_at->isoFormat('D MMMM Y, HH:mm') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Tugas
                    </a>
                @endcan

                @can('delete', $task)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-danger text-xs font-semibold transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Details, Description, and Comments -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Main Card: Overview & Description -->
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <!-- Priority badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $task->priority === 'high' ? 'bg-red-50 text-danger border border-red-200' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                Prioritas {{ ucfirst($task->priority) }}
                            </span>

                            <!-- Status badge -->
                            @if($task->status === 'done')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Done / Selesai
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    In Progress
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    To Do
                                </span>
                            @endif
                        </div>

                        <!-- Status Quick Switcher Form -->
                        <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <label for="quick_status" class="text-xs text-textsec font-medium">Ubah Status:</label>
                            <select id="quick_status" name="status" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-semibold">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </form>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-xs font-semibold text-textsec uppercase tracking-wider mb-2">Deskripsi Tugas</h3>
                        @if($task->description)
                            <div class="prose max-w-none text-sm text-textmain whitespace-pre-line leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                                {{ $task->description }}
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Tidak ada deskripsi yang dicantumkan untuk tugas ini.</p>
                        @endif
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-textmain">Komentar & Diskusi</h2>
                        <span class="text-xs font-semibold text-textsec bg-gray-100 px-2.5 py-0.5 rounded-full">{{ $task->comments->count() }}</span>
                    </div>

                    <!-- Add Comment Form -->
                    <form method="POST" action="{{ route('comments.store', $task) }}" class="space-y-3">
                        @csrf
                        <div>
                            <textarea name="body" rows="3" required placeholder="Tulis komentar atau update progres tugas..." 
                                      class="w-full text-xs sm:text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                Kirim Komentar
                            </button>
                        </div>
                    </form>

                    <!-- Comments List -->
                    @if($task->comments->isEmpty())
                        <p class="text-xs text-textsec py-4 text-center">Belum ada komentar pada tugas ini. Jadilah yang pertama memberikan masukan.</p>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach($task->comments as $comment)
                                <div class="py-4 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-primary/10 text-primary text-xs font-bold flex items-center justify-center">
                                                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-textmain">{{ $comment->user->name }}</span>
                                                <span class="text-[10px] text-textsec ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        @if($comment->user_id === Auth::id() || $task->created_by === Auth::id() || ($task->project && $task->project->user_id === Auth::id()))
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Hapus komentar ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-textsec hover:text-danger p-1 rounded transition" title="Hapus Komentar">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="text-xs text-textmain whitespace-pre-line pl-9">{{ $comment->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right 1 Col: Metadata Sidebar & Activity Log -->
            <div class="space-y-6">
                
                <!-- Metadata Card -->
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
                    <h3 class="text-xs font-semibold text-textsec uppercase tracking-wider">Informasi Tugas</h3>

                    <!-- Project -->
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-textsec">Proyek:</span>
                        @if($task->project)
                            <a href="{{ route('projects.show', $task->project) }}" class="font-semibold text-primary hover:underline">
                                {{ $task->project->name }}
                            </a>
                        @else
                            <span class="text-gray-400">Pribadi</span>
                        @endif
                    </div>

                    <!-- Category -->
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-textsec">Kategori:</span>
                        @if($task->category)
                            <span class="font-medium text-textmain">#{{ $task->category->name }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>

                    <!-- Assignee -->
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-textsec">Penanggung Jawab:</span>
                        @if($task->assignee)
                            <div class="flex items-center gap-1.5 font-medium text-textmain">
                                <div class="w-5 h-5 rounded-full bg-primary/10 text-primary text-[9px] font-bold flex items-center justify-center">
                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                </div>
                                <span>{{ $task->assignee->name }}</span>
                            </div>
                        @else
                            <span class="text-gray-400">Belum Ditugaskan</span>
                        @endif
                    </div>

                    <!-- Start Date -->
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-textsec">Tanggal Mulai:</span>
                        <span class="font-medium text-textmain">
                            {{ $task->start_date ? $task->start_date->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <!-- Due Date -->
                    <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                        <span class="text-textsec">Deadline:</span>
                        @if($task->due_date)
                            <div class="text-right">
                                <span class="font-bold {{ $task->isOverdue() ? 'text-danger' : ($task->isDueSoon() ? 'text-amber-600' : 'text-textmain') }}">
                                    {{ $task->due_date->format('d M Y') }}
                                </span>
                                @if($task->isOverdue())
                                    <span class="block text-[10px] text-danger font-semibold">Melewati Deadline!</span>
                                @elseif($task->isDueSoon())
                                    <span class="block text-[10px] text-amber-600 font-semibold">Jatuh Tempo Segera</span>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">Tidak ada deadline</span>
                        @endif
                    </div>

                    <!-- Completed At -->
                    @if($task->completed_at)
                        <div class="flex items-center justify-between text-xs py-1">
                            <span class="text-textsec">Waktu Selesai:</span>
                            <span class="font-medium text-emerald-600">
                                {{ $task->completed_at->isoFormat('D MMM Y, HH:mm') }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Task Activity Log -->
                <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
                    <h3 class="text-xs font-semibold text-textsec uppercase tracking-wider">Riwayat Aktivitas</h3>

                    @if($task->activities->isEmpty())
                        <p class="text-xs text-textsec py-2 text-center">Belum ada riwayat tercatat.</p>
                    @else
                        <div class="flow-root">
                            <ul role="list" class="-mb-6 space-y-3">
                                @foreach($task->activities as $activity)
                                    <li class="relative flex gap-x-3 text-xs">
                                        <div class="relative flex h-5 w-5 flex-none items-center justify-center bg-surface">
                                            <div class="h-1.5 w-1.5 rounded-full bg-primary ring-2 ring-primary/20"></div>
                                        </div>
                                        <div class="flex-auto">
                                            <p class="text-textsec leading-tight">
                                                <strong class="font-semibold text-textmain">{{ $activity->user->name }}</strong>
                                                @if($activity->action === 'created')
                                                    membuat tugas ini.
                                                @elseif($activity->action === 'status_updated')
                                                    mengubah status dari <span class="font-semibold">{{ $activity->old_value }}</span> ke <span class="font-semibold text-primary">{{ $activity->new_value }}</span>.
                                                @elseif($activity->action === 'priority_updated')
                                                    mengubah prioritas ke <span class="font-semibold text-textmain">{{ $activity->new_value }}</span>.
                                                @elseif($activity->action === 'due_date_updated')
                                                    mengubah deadline menjadi <span class="font-semibold text-textmain">{{ $activity->new_value ?? 'dihapus' }}</span>.
                                                @elseif($activity->action === 'assigned')
                                                    menugaskan ke <span class="font-semibold text-textmain">{{ $activity->new_value }}</span>.
                                                @else
                                                    memperbarui tugas.
                                                @endif
                                            </p>
                                            <span class="text-[10px] text-gray-400 block mt-0.5">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
