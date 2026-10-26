<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Notifikasi</h1>
                <p class="text-xs text-textsec mt-0.5">Pemberitahuan deadline, penugasan tugas, dan aktivitas proyek Anda.</p>
            </div>
            
            @if(Auth::user()->unreadNotificationsCount() > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-textmain text-xs font-semibold rounded-xl shadow-2xs transition">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Tandai Semua Telah Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
            @if($notifications->isEmpty())
                <div class="py-16 text-center text-textsec text-sm">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-textmain">Belum ada notifikasi</h3>
                    <p class="text-xs text-textsec mt-1">Anda sudah melihat seluruh pembaruan sistem dan deadline tugas.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($notifications as $notif)
                        <div class="p-4 sm:p-5 flex items-start justify-between gap-4 transition {{ is_null($notif->read_at) ? 'bg-primary/5' : 'hover:bg-gray-50/70' }}">
                            
                            <div class="flex items-start gap-3.5">
                                <!-- Type Icon -->
                                <div class="mt-0.5 shrink-0">
                                    @if($notif->type === 'overdue')
                                        <div class="w-8 h-8 rounded-xl bg-red-100 text-danger flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @elseif($notif->type === 'deadline_approaching')
                                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @elseif($notif->type === 'task_assigned')
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <p class="text-sm text-textmain leading-snug">
                                        {{ $notif->message }}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-textsec">
                                        <span>{{ $notif->created_at->diffForHumans() }}</span>
                                        @if(is_null($notif->read_at))
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-bold bg-primary text-white">Baru</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="shrink-0 flex items-center gap-2">
                                @if($notif->task_id)
                                    <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary text-xs font-semibold rounded-lg transition">
                                            Buka Tugas
                                        </button>
                                    </form>
                                @elseif(is_null($notif->read_at))
                                    <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 border border-gray-200 hover:bg-gray-50 text-textsec text-xs font-semibold rounded-lg transition">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
