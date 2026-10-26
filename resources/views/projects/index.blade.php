<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-textmain">Daftar Proyek</h1>
                <p class="text-xs text-textsec mt-0.5">Kelola proyek tim dan pribadi Anda, pantau progres dan anggota.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Proyek Baru
            </a>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @if($projects->isEmpty())
            <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs py-16 text-center">
                <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-textmain">Belum ada proyek dibuat</h3>
                <p class="text-xs text-textsec mt-1 max-w-sm mx-auto">Organisasikan tugas-tugas Anda ke dalam proyek kolaboratif atau proyek pribadi.</p>
                <div class="mt-4">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold shadow-xs hover:bg-primary-600 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Proyek Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    @php
                        $progress = $project->calculateProgress();
                        $isOwner = $project->isOwner(Auth::user());
                        $totalCount = $project->tasks->count();
                        $doneCount = $project->tasks->where('status', 'done')->count();
                    @endphp
                    <div class="bg-surface rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col overflow-hidden">
                        <!-- Color top stripe -->
                        <div class="h-2 w-full" style="background-color: {{ $project->color ?? '#2F855A' }};"></div>
                        
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $isOwner ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-textsec' }}">
                                        {{ $isOwner ? 'Pemilik (Owner)' : 'Anggota (Member)' }}
                                    </span>
                                    <span class="text-xs font-semibold text-textsec">{{ $doneCount }}/{{ $totalCount }} Selesai</span>
                                </div>

                                <a href="{{ route('projects.show', $project) }}" class="block text-lg font-bold text-textmain hover:text-primary transition">
                                    {{ $project->name }}
                                </a>

                                <p class="text-xs text-textsec line-clamp-2 mt-1">
                                    {{ $project->description ?: 'Tidak ada deskripsi proyek.' }}
                                </p>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5 pt-2">
                                <div class="flex justify-between text-xs font-medium">
                                    <span class="text-textsec">Progres</span>
                                    <span class="text-textmain font-bold">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $progress }}%; background-color: {{ $project->color ?? '#2F855A' }};"></div>
                                </div>
                            </div>

                            <!-- Footer: Team & Detail Link -->
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center -space-x-1.5 overflow-hidden">
                                    <!-- Owner -->
                                    <div class="inline-block h-6 w-6 rounded-full bg-primary text-white text-[9px] font-bold ring-2 ring-white flex items-center justify-center" title="Owner: {{ $project->owner->name }}">
                                        {{ strtoupper(substr($project->owner->name, 0, 2)) }}
                                    </div>
                                    <!-- Members preview -->
                                    @foreach($project->members->take(3) as $member)
                                        <div class="inline-block h-6 w-6 rounded-full bg-gray-200 text-gray-700 text-[9px] font-bold ring-2 ring-white flex items-center justify-center" title="{{ $member->name }}">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                    @endforeach
                                    @if($project->members->count() > 3)
                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-[9px] font-bold text-textsec ring-2 ring-white">
                                            +{{ $project->members->count() - 3 }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                                    Buka Proyek
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $projects->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
