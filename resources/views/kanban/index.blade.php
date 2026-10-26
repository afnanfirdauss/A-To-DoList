<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Kanban Board</h1>
                <p class="text-xs text-textsec mt-0.5">Visualisasikan dan geser (drag & drop) alur kerja tugas Anda secara real-time.</p>
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

        <!-- Filters Toolbar -->
        <div class="bg-surface p-4 rounded-2xl border border-gray-100 shadow-xs">
            <form method="GET" action="{{ route('kanban.index') }}" class="flex flex-wrap items-center gap-3">
                <!-- Project Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-textsec">Proyek:</label>
                    <select name="project_id" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-textsec">Prioritas:</label>
                    <select name="priority" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                    </select>
                </div>

                <!-- Assignee Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-textsec">Penanggung Jawab:</label>
                    <select name="assigned_to" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                        <option value="">Semua Anggota</option>
                        @foreach($assignees as $u)
                            <option value="{{ $u->id }}" {{ request('assigned_to') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request()->anyFilled(['project_id', 'priority', 'assigned_to']))
                    <a href="{{ route('kanban.index') }}" class="text-xs text-textsec hover:text-danger underline ml-auto">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Global Toast / Status Banner for Kanban Updates -->
        <div id="kanban-toast" class="hidden fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg text-xs font-semibold transition">
            <span id="kanban-toast-icon"></span>
            <span id="kanban-toast-msg"></span>
        </div>

        <!-- Kanban 3 Columns Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            
            <!-- Column 1: To Do -->
            <div class="bg-gray-100/70 p-4 rounded-2xl border border-gray-200/60 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                        <h2 class="text-sm font-bold text-textmain">To Do</h2>
                    </div>
                    <span id="count-todo" class="text-xs font-bold px-2 py-0.5 rounded-full bg-white text-textsec shadow-2xs">
                        {{ $todoTasks->count() }}
                    </span>
                </div>

                <!-- Cards Container -->
                <div id="kanban-col-todo" data-status="todo" class="kanban-dropzone flex-1 space-y-3 pt-3">
                    @forelse($todoTasks as $task)
                        @include('kanban.task-card', ['task' => $task])
                    @empty
                        <div class="empty-placeholder text-center py-8 text-xs text-textsec">
                            Belum ada tugas To Do
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 2: In Progress -->
            <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-200/50 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-3 border-b border-amber-200/60">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <h2 class="text-sm font-bold text-amber-900">In Progress</h2>
                    </div>
                    <span id="count-in_progress" class="text-xs font-bold px-2 py-0.5 rounded-full bg-white text-amber-800 shadow-2xs">
                        {{ $inProgressTasks->count() }}
                    </span>
                </div>

                <!-- Cards Container -->
                <div id="kanban-col-in_progress" data-status="in_progress" class="kanban-dropzone flex-1 space-y-3 pt-3">
                    @forelse($inProgressTasks as $task)
                        @include('kanban.task-card', ['task' => $task])
                    @empty
                        <div class="empty-placeholder text-center py-8 text-xs text-textsec">
                            Belum ada tugas In Progress
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Column 3: Done -->
            <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-200/50 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between pb-3 border-b border-emerald-200/60">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h2 class="text-sm font-bold text-emerald-900">Done</h2>
                    </div>
                    <span id="count-done" class="text-xs font-bold px-2 py-0.5 rounded-full bg-white text-emerald-800 shadow-2xs">
                        {{ $doneTasks->count() }}
                    </span>
                </div>

                <!-- Cards Container -->
                <div id="kanban-col-done" data-status="done" class="kanban-dropzone flex-1 space-y-3 pt-3">
                    @forelse($doneTasks as $task)
                        @include('kanban.task-card', ['task' => $task])
                    @empty
                        <div class="empty-placeholder text-center py-8 text-xs text-textsec">
                            Belum ada tugas selesai
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const dropzones = document.querySelectorAll('.kanban-dropzone');

            function showToast(message, isSuccess = true) {
                const toast = document.getElementById('kanban-toast');
                const toastMsg = document.getElementById('kanban-toast-msg');
                const toastIcon = document.getElementById('kanban-toast-icon');

                toast.className = `fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg text-xs font-semibold transition ${
                    isSuccess ? 'bg-emerald-800 text-white' : 'bg-danger text-white'
                }`;
                toastIcon.innerHTML = isSuccess 
                    ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    : '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                toastMsg.textContent = message;
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3500);
            }

            function updateCounters() {
                ['todo', 'in_progress', 'done'].forEach(status => {
                    const zone = document.getElementById(`kanban-col-${status}`);
                    const countBadge = document.getElementById(`count-${status}`);
                    const cards = zone.querySelectorAll('.kanban-task-card');
                    if (countBadge) countBadge.textContent = cards.length;

                    const placeholder = zone.querySelector('.empty-placeholder');
                    if (cards.length === 0) {
                        if (!placeholder) {
                            const newPh = document.createElement('div');
                            newPh.className = 'empty-placeholder text-center py-8 text-xs text-textsec';
                            newPh.textContent = `Belum ada tugas ${status.replace('_', ' ')}`;
                            zone.appendChild(newPh);
                        }
                    } else if (placeholder) {
                        placeholder.remove();
                    }
                });
            }

            // Initialize SortableJS on all columns
            dropzones.forEach(zone => {
                new Sortable(zone, {
                    group: 'kanban-board',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    filter: '.empty-placeholder, select, a, button',
                    preventOnFilter: false,
                    onEnd: function (evt) {
                        const item = evt.item;
                        const taskId = item.getAttribute('data-task-id');
                        const newStatus = evt.to.getAttribute('data-status');
                        const oldStatus = evt.from.getAttribute('data-status');

                        if (newStatus === oldStatus) return;

                        // Visual loading state
                        item.classList.add('opacity-50', 'pointer-events-none');

                        fetch(`/tasks/${taskId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ status: newStatus })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal memperbarui status tugas.');
                            return response.json();
                        })
                        .then(data => {
                            item.classList.remove('opacity-50', 'pointer-events-none');
                            
                            // Update dropdown status selector inside the card
                            const statusSelect = item.querySelector('.card-status-select');
                            if (statusSelect) statusSelect.value = newStatus;

                            showToast('Status tugas berhasil diperbarui ke ' + newStatus.replace('_', ' ').toUpperCase());
                            updateCounters();
                        })
                        .catch(err => {
                            item.classList.remove('opacity-50', 'pointer-events-none');
                            evt.from.appendChild(item); // Revert move
                            showToast(err.message || 'Terjadi kesalahan sistem', false);
                            updateCounters();
                        });
                    }
                });
            });

            // Handle dropdown change event fallback
            document.addEventListener('change', function (e) {
                if (e.target && e.target.classList.contains('card-status-select')) {
                    const card = e.target.closest('.kanban-task-card');
                    const taskId = card.getAttribute('data-task-id');
                    const newStatus = e.target.value;
                    const targetZone = document.getElementById(`kanban-col-${newStatus}`);

                    if (!targetZone) return;

                    card.classList.add('opacity-50', 'pointer-events-none');

                    fetch(`/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal mengubah status.');
                        return response.json();
                    })
                    .then(() => {
                        card.classList.remove('opacity-50', 'pointer-events-none');
                        targetZone.appendChild(card);
                        showToast('Status berhasil diubah ke ' + newStatus.replace('_', ' ').toUpperCase());
                        updateCounters();
                    })
                    .catch(err => {
                        card.classList.remove('opacity-50', 'pointer-events-none');
                        showToast(err.message, false);
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
