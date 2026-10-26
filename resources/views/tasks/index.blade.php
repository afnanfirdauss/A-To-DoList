<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Daftar Tugas</h1>
                <p class="text-xs text-textsec mt-0.5">Kelola, cari, dan pantau seluruh tugas pribadi maupun tim Anda.</p>
            </div>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Tugas
            </a>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Filters & Search Toolbar -->
        <div class="bg-surface p-4 rounded-2xl border border-gray-100 shadow-xs">
            <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] font-semibold text-textsec uppercase mb-1">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul tugas..." 
                                   class="w-full text-xs pl-8 pr-3 py-2 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <label class="block text-[11px] font-semibold text-textsec uppercase mb-1">Status</label>
                        <select name="status" class="w-full text-xs py-2 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">Semua Status</option>
                            <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <!-- Filter Priority -->
                    <div>
                        <label class="block text-[11px] font-semibold text-textsec uppercase mb-1">Prioritas</label>
                        <select name="priority" class="w-full text-xs py-2 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">Semua Prioritas</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                        </select>
                    </div>

                    <!-- Filter Project -->
                    <div>
                        <label class="block text-[11px] font-semibold text-textsec uppercase mb-1">Proyek</label>
                        <select name="project_id" class="w-full text-xs py-2 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="">Semua Proyek</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div>
                        <label class="block text-[11px] font-semibold text-textsec uppercase mb-1">Urutan</label>
                        <select name="sort" class="w-full text-xs py-2 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-xs">
                            <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Terbaru Dibuat</option>
                            <option value="due_date_asc" {{ request('sort') === 'due_date_asc' ? 'selected' : '' }}>Deadline Terdekat</option>
                            <option value="due_date_desc" {{ request('sort') === 'due_date_desc' ? 'selected' : '' }}>Deadline Terjauh</option>
                            <option value="priority_desc" {{ request('sort') === 'priority_desc' ? 'selected' : '' }}>Prioritas Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-textsec">Filter Deadline:</span>
                        <a href="{{ route('tasks.index', array_merge(request()->except('deadline_filter', 'page'), ['deadline_filter' => 'overdue'])) }}" 
                           class="px-2.5 py-1 text-xs rounded-lg {{ request('deadline_filter') === 'overdue' ? 'bg-red-100 text-danger font-semibold' : 'bg-gray-100 text-textsec hover:bg-gray-200' }}">
                            Terlambat
                        </a>
                        <a href="{{ route('tasks.index', array_merge(request()->except('deadline_filter', 'page'), ['deadline_filter' => 'due_soon'])) }}" 
                           class="px-2.5 py-1 text-xs rounded-lg {{ request('deadline_filter') === 'due_soon' ? 'bg-amber-100 text-amber-800 font-semibold' : 'bg-gray-100 text-textsec hover:bg-gray-200' }}">
                            Jatuh Tempo Segera
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['search', 'status', 'priority', 'project_id', 'category_id', 'deadline_filter', 'sort']))
                            <a href="{{ route('tasks.index') }}" class="text-xs text-textsec hover:text-danger underline">Reset Filter</a>
                        @endif
                        <button type="submit" class="px-4 py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primary-600 transition">
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tasks Table / Card List -->
        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
            @if($tasks->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-textmain">Tidak ada tugas ditemukan</h3>
                    <p class="text-xs text-textsec mt-1 max-w-sm mx-auto">Tidak ada tugas yang sesuai dengan kriteria filter atau pencarian Anda. Coba reset filter atau buat tugas baru.</p>
                    <div class="mt-4">
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold shadow-xs hover:bg-primary-600 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Tugas Pertama
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                        <thead class="bg-gray-50 text-textsec uppercase text-[10px] font-semibold tracking-wider">
                            <tr>
                                <th scope="col" class="py-3 px-4">Tugas</th>
                                <th scope="col" class="py-3 px-4">Proyek & Kategori</th>
                                <th scope="col" class="py-3 px-4">Prioritas</th>
                                <th scope="col" class="py-3 px-4">Deadline</th>
                                <th scope="col" class="py-3 px-4">Penanggung Jawab</th>
                                <th scope="col" class="py-3 px-4">Status</th>
                                <th scope="col" class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($tasks as $task)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <!-- Title & Description preview -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-start gap-2.5">
                                            <form method="POST" action="{{ route('tasks.update-status', $task) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $task->status === 'done' ? 'todo' : 'done' }}">
                                                <button type="submit" title="Klik untuk ubah status" class="mt-0.5 rounded text-primary focus:ring-primary h-4 w-4 border-gray-300 transition">
                                                    @if($task->status === 'done')
                                                        <svg class="w-4 h-4 text-primary fill-current" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <div class="w-4 h-4 rounded border-2 border-gray-300 hover:border-primary"></div>
                                                    @endif
                                                </button>
                                            </form>
                                            <div>
                                                <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-textmain hover:text-primary {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">
                                                    {{ $task->title }}
                                                </a>
                                                @if($task->description)
                                                    <p class="text-textsec text-[11px] truncate max-w-xs mt-0.5">{{ Str::limit($task->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Project & Category -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            @if($task->project)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800">
                                                    {{ $task->project->name }}
                                                </span>
                                            @else
                                                <span class="text-[11px] text-gray-400">Pribadi</span>
                                            @endif

                                            @if($task->category)
                                                <div class="text-[10px] text-textsec">
                                                    #{{ $task->category->name }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Priority Badge -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $task->priority === 'high' ? 'bg-red-50 text-danger border border-red-200' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>

                                    <!-- Due Date -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($task->due_date)
                                            <div class="flex items-center gap-1.5">
                                                <span class="{{ $task->isOverdue() ? 'text-danger font-bold' : ($task->isDueSoon() ? 'text-amber-600 font-semibold' : 'text-textmain') }}">
                                                    {{ $task->due_date->format('d M Y') }}
                                                </span>
                                                @if($task->isOverdue())
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-red-100 text-danger">Terlambat</span>
                                                @elseif($task->isDueSoon())
                                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-amber-100 text-amber-700">Segera</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Assignee -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-primary/10 text-primary text-[10px] font-bold flex items-center justify-center">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                                                </div>
                                                <span class="text-textmain font-medium">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    <!-- Status Dropdown / Badge -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs py-1 px-2.5 rounded-lg border-gray-200 focus:border-primary focus:ring-primary font-medium cursor-pointer {{ $task->status === 'done' ? 'bg-emerald-50 text-emerald-800' : ($task->status === 'in_progress' ? 'bg-amber-50 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                            </select>
                                        </form>
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="py-3.5 px-4 whitespace-nowrap text-right space-x-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="p-1.5 text-textsec hover:text-primary rounded-lg transition inline-block" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @can('update', $task)
                                            <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 text-textsec hover:text-amber-600 rounded-lg transition inline-block" title="Edit Tugas">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('delete', $task)
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-textsec hover:text-danger rounded-lg transition" title="Hapus Tugas">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
