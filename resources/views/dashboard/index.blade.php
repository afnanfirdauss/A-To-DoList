<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Productivity Dashboard</h1>
                <p class="text-xs text-textsec mt-0.5">Ringkasan aktivitas, progres tugas, dan analitik produktivitas Anda.</p>
            </div>
            <!-- Project Context Filter -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <label for="project_id" class="text-xs font-medium text-textsec shrink-0">Filter Proyek:</label>
                <select name="project_id" id="project_id" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-lg border-gray-200 bg-surface shadow-xs focus:border-primary focus:ring-primary text-textmain">
                    <option value="">Semua Proyek & Pribadi</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ $selectedProjectId == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        
        <!-- Top Metrics Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Tasks -->
            <div class="bg-surface p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-textsec uppercase tracking-wider">Total Tugas</p>
                    <h3 class="text-3xl font-extrabold text-textmain mt-1">{{ $totalTasks }}</h3>
                    <p class="text-[11px] text-textsec mt-1">Dalam konteks aktif</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Completed Tasks & Rate -->
            <div class="bg-surface p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-textsec uppercase tracking-wider">Tugas Selesai</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $completedTasks }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            {{ $completionRate }}%
                        </span>
                    </div>
                    <p class="text-[11px] text-textsec mt-1">Tingkat penyelesaian</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Card 3: In Progress & Todo -->
            <div class="bg-surface p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-textsec uppercase tracking-wider">Sedang Dikerjakan</p>
                    <div class="flex items-baseline gap-3 mt-1">
                        <h3 class="text-3xl font-extrabold text-amber-600">{{ $inProgressTasks }}</h3>
                        <span class="text-xs text-textsec">/ {{ $todoTasks }} To-Do</span>
                    </div>
                    <p class="text-[11px] text-textsec mt-1">Tugas aktif berjalan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Card 4: Overdue & Due Soon -->
            <div class="bg-surface p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-textsec uppercase tracking-wider">Deadline Kritis</p>
                    <div class="flex items-center gap-2 mt-1">
                        <h3 class="text-3xl font-extrabold text-danger">{{ $overdueTasks }}</h3>
                        <span class="text-xs text-danger font-medium">Terlambat</span>
                    </div>
                    <p class="text-[11px] text-amber-600 font-medium mt-1">{{ $dueSoonTasks }} tugas jatuh tempo segera</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-danger flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section (Chart.js) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Weekly Productivity Chart (2 Cols) -->
            <div class="lg:col-span-2 bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-textmain">Grafik Produktivitas Mingguan</h2>
                        <p class="text-xs text-textsec">Perbandingan tugas diselesaikan vs tugas dibuat (7 hari terakhir)</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-50 text-textsec border border-gray-100">7 Hari</span>
                </div>
                <div class="h-64">
                    <canvas id="weeklyProductivityChart"></canvas>
                </div>
            </div>

            <!-- Priority Breakdown Chart (1 Col) -->
            <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-textmain">Distribusi Prioritas</h2>
                        <p class="text-xs text-textsec">Proporsi beban kerja aktif</p>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Two Column Lists: Upcoming Deadlines & Recent Tasks -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Upcoming Deadlines -->
            <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h2 class="text-base font-bold text-textmain">Mendekati Deadline</h2>
                    </div>
                    <a href="{{ route('tasks.index', ['deadline_filter' => 'due_soon']) }}" class="text-xs font-semibold text-primary hover:underline">Lihat Semua</a>
                </div>

                @if($upcomingTasks->isEmpty())
                    <div class="py-8 text-center text-textsec text-sm">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tidak ada tugas yang mendekati deadline saat ini. Kerja bagus!
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($upcomingTasks as $task)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-textmain hover:text-primary truncate block">
                                        {{ $task->title }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-textsec">
                                        @if($task->project)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700">
                                                {{ $task->project->name }}
                                            </span>
                                        @else
                                            <span class="text-[10px] text-gray-400">Pribadi</span>
                                        @endif
                                        <span>•</span>
                                        <span class="{{ $task->isOverdue() ? 'text-danger font-bold' : ($task->isDueSoon() ? 'text-amber-600 font-medium' : '') }}">
                                            {{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="shrink-0 px-2.5 py-1 text-xs font-medium rounded-full {{ $task->priority === 'high' ? 'bg-red-50 text-danger' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Tasks -->
            <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h2 class="text-base font-bold text-textmain">Tugas Terbaru</h2>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat Semua</a>
                </div>

                @if($recentTasks->isEmpty())
                    <div class="py-8 text-center text-textsec text-sm">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Belum ada tugas dibuat. Klik "Create Task" untuk memulai!
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentTasks as $task)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-textmain hover:text-primary truncate block">
                                        {{ $task->title }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-textsec">
                                        <span>Dibuat {{ $task->created_at->diffForHumans() }}</span>
                                        @if($task->project)
                                            <span>•</span>
                                            <span>{{ $task->project->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    @if($task->status === 'done')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Done</span>
                                    @elseif($task->status === 'in_progress')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">In Progress</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">To Do</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities Feed -->
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
            <h2 class="text-base font-bold text-textmain mb-4">Aktivitas Terakhir</h2>
            @if($recentActivities->isEmpty())
                <p class="text-xs text-textsec py-4 text-center">Belum ada rekaman aktivitas tugas.</p>
            @else
                <div class="flow-root">
                    <ul role="list" class="-mb-6 space-y-4">
                        @foreach($recentActivities as $activity)
                            <li class="relative flex gap-x-4">
                                <div class="relative flex h-6 w-6 flex-none items-center justify-center bg-surface">
                                    <div class="h-2 w-2 rounded-full bg-primary ring-4 ring-primary/10"></div>
                                </div>
                                <p class="flex-auto py-0.5 text-xs leading-5 text-textsec">
                                    <strong class="font-semibold text-textmain">{{ $activity->user->name }}</strong>
                                    @if($activity->action === 'created')
                                        membuat tugas <a href="{{ route('tasks.show', $activity->task) }}" class="font-medium text-primary hover:underline">'{{ $activity->task->title }}'</a>.
                                    @elseif($activity->action === 'status_updated')
                                        mengubah status tugas <a href="{{ route('tasks.show', $activity->task) }}" class="font-medium text-primary hover:underline">'{{ $activity->task->title }}'</a> dari <span class="font-semibold text-textmain">{{ $activity->old_value }}</span> menjadi <span class="font-semibold text-primary">{{ $activity->new_value }}</span>.
                                    @elseif($activity->action === 'priority_updated')
                                        mengubah prioritas tugas <a href="{{ route('tasks.show', $activity->task) }}" class="font-medium text-primary hover:underline">'{{ $activity->task->title }}'</a> ke <span class="font-semibold text-textmain">{{ $activity->new_value }}</span>.
                                    @elseif($activity->action === 'assigned')
                                        menetapkan tugas <a href="{{ route('tasks.show', $activity->task) }}" class="font-medium text-primary hover:underline">'{{ $activity->task->title }}'</a> kepada <span class="font-semibold text-textmain">{{ $activity->new_value }}</span>.
                                    @else
                                        memperbarui tugas <a href="{{ route('tasks.show', $activity->task) }}" class="font-medium text-primary hover:underline">'{{ $activity->task->title }}'</a>.
                                    @endif
                                </p>
                                <time class="flex-none py-0.5 text-[11px] text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </time>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Weekly Productivity Chart
            const weeklyCtx = document.getElementById('weeklyProductivityChart').getContext('2d');
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: @json($weeklyDays),
                    datasets: [
                        {
                            label: 'Tugas Selesai',
                            data: @json($weeklyCompleted),
                            backgroundColor: '#2F855A',
                            borderRadius: 6,
                        },
                        {
                            label: 'Tugas Dibuat',
                            data: @json($weeklyCreated),
                            backgroundColor: '#E2E8F0',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter', size: 12 },
                                boxWidth: 12,
                                usePointStyle: true,
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter', size: 11 } },
                            grid: { color: '#F1F5F9' }
                        },
                        x: {
                            ticks: { font: { family: 'Inter', size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Priority Distribution Doughnut Chart
            const priorityCtx = document.getElementById('priorityChart').getContext('2d');
            new Chart(priorityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tinggi', 'Sedang', 'Rendah'],
                    datasets: [{
                        data: [
                            {{ $priorityBreakdown['high'] }},
                            {{ $priorityBreakdown['medium'] }},
                            {{ $priorityBreakdown['low'] }}
                        ],
                        backgroundColor: ['#DC2626', '#D97706', '#3B82F6'],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
