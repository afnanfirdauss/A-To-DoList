<div class="kanban-task-card bg-surface p-4 rounded-xl border border-gray-100 shadow-2xs hover:shadow-md transition cursor-grab active:cursor-grabbing group"
     data-task-id="{{ $task->id }}">
    
    <!-- Top Row: Project Badge & Priority Badge -->
    <div class="flex items-center justify-between gap-2 mb-2">
        <div>
            @if($task->project)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-700">
                    {{ $task->project->name }}
                </span>
            @else
                <span class="text-[10px] text-gray-400">Pribadi</span>
            @endif
        </div>
        
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $task->priority === 'high' ? 'bg-red-50 text-danger border border-red-200' : ($task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200') }}">
            {{ ucfirst($task->priority) }}
        </span>
    </div>

    <!-- Title -->
    <a href="{{ route('tasks.show', $task) }}" class="block text-sm font-semibold text-textmain group-hover:text-primary transition mb-2">
        {{ $task->title }}
    </a>

    <!-- Bottom Row: Due Date, Assignee, and Accessibility Dropdown -->
    <div class="flex items-center justify-between pt-2 border-t border-gray-50 text-xs">
        <!-- Due Date -->
        <div class="flex items-center gap-1">
            @if($task->due_date)
                <svg class="w-3.5 h-3.5 {{ $task->isOverdue() ? 'text-danger' : ($task->isDueSoon() ? 'text-amber-600' : 'text-textsec') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-[11px] {{ $task->isOverdue() ? 'text-danger font-bold' : ($task->isDueSoon() ? 'text-amber-600 font-semibold' : 'text-textsec') }}">
                    {{ $task->due_date->format('d M') }}
                </span>
            @else
                <span class="text-[11px] text-gray-300">-</span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <!-- Assignee avatar -->
            @if($task->assignee)
                <div class="w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-[9px] flex items-center justify-center" title="Penanggung Jawab: {{ $task->assignee->name }}">
                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                </div>
            @endif

            <!-- Accessibility Fallback Status Selector -->
            <select class="card-status-select text-[10px] py-0.5 px-1.5 rounded-lg border-gray-200 focus:border-primary focus:ring-primary text-textsec bg-gray-50 cursor-pointer" title="Ubah status">
                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>
    </div>
</div>
