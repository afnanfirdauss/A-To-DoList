<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Kalender Deadline</h1>
                <p class="text-xs text-textsec mt-0.5">Pantau jadwal dan tenggat waktu penyelesaian tugas dalam tampilan kalender.</p>
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

        <!-- Toolbar & Legend -->
        <div class="bg-surface p-4 rounded-2xl border border-gray-100 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <!-- Project Filter -->
            <div class="flex items-center gap-2">
                <label for="calendar-project-filter" class="text-xs font-semibold text-textsec">Filter Proyek:</label>
                <select id="calendar-project-filter" class="text-xs py-1.5 px-3 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-2xs">
                    <option value="">Semua Proyek & Pribadi</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-3 text-xs text-textsec">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#2F855A]"></span>
                    <span>To Do</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#D97706]"></span>
                    <span>In Progress</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#16A34A]"></span>
                    <span>Done</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#DC2626]"></span>
                    <span>Terlambat (Overdue)</span>
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="bg-surface p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div id="fullcalendar-view"></div>
        </div>

    </div>

    @push('styles')
    <style>
        .fc {
            font-family: 'Inter', sans-serif !important;
        }
        .fc .fc-toolbar-title {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #20252B !important;
        }
        .fc .fc-button-primary {
            background-color: #FFFFFF !important;
            color: #20252B !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.75rem !important;
            padding: 0.4rem 0.8rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .fc .fc-button-primary:hover {
            background-color: #F8FAFC !important;
            border-color: #CBD5E1 !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background-color: #2F855A !important;
            color: #FFFFFF !important;
            border-color: #2F855A !important;
        }
        .fc .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: transform 0.15s ease !important;
        }
        .fc .fc-event:hover {
            transform: scale(1.02);
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #F1F5F9 !important;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #6B7280 !important;
            padding: 8px 0 !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('fullcalendar-view');
            const projectFilter = document.getElementById('calendar-project-filter');

            const calendar = new window.FullCalendar.Calendar(calendarEl, {
                plugins: [
                    window.FullCalendar.dayGridPlugin,
                    window.FullCalendar.timeGridPlugin,
                    window.FullCalendar.listPlugin,
                    window.FullCalendar.interactionPlugin,
                ],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                buttonText: {
                    today: 'Hari Ini',
                    dayGridMonth: 'Bulan',
                    timeGridWeek: 'Minggu',
                    listMonth: 'Daftar'
                },
                events: function(info, successCallback, failureCallback) {
                    const projectId = projectFilter.value;
                    let url = `/calendar/events?start=${info.startStr}&end=${info.endStr}`;
                    if (projectId) {
                        url += `&project_id=${projectId}`;
                    }

                    fetch(url, { credentials: 'same-origin' })
                        .then(response => response.json())
                        .then(events => successCallback(events))
                        .catch(err => failureCallback(err));
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();

            projectFilter.addEventListener('change', function () {
                calendar.refetchEvents();
            });
        });
    </script>
    @endpush
</x-app-layout>
