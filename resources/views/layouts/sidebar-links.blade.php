@php
    $currentRoute = Route::currentRouteName();
    $unreadCount = Auth::user()->unreadNotificationsCount();
@endphp

<!-- Dashboard -->
<a href="{{ route('dashboard') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    <span>Dashboard</span>
</a>

<!-- My Tasks -->
<a href="{{ route('tasks.index') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'tasks') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'tasks') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
    </svg>
    <span>My Tasks</span>
</a>

<!-- Kanban Board -->
<a href="{{ route('kanban.index') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'kanban') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'kanban') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
    </svg>
    <span>Kanban Board</span>
</a>

<!-- Calendar -->
<a href="{{ route('calendar.index') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'calendar') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'calendar') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <span>Calendar</span>
</a>

<!-- Projects -->
<a href="{{ route('projects.index') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'projects') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'projects') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
    </svg>
    <span>Projects</span>
</a>

<!-- Categories -->
<a href="{{ route('categories.index') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'categories') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'categories') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
    </svg>
    <span>Categories</span>
</a>

<!-- Notifications -->
<a href="{{ route('notifications.index') }}" 
   class="group flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'notifications') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'notifications') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span>Notifications</span>
    </div>
    @if ($unreadCount > 0)
        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-danger rounded-full">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    @endif
</a>

<!-- Settings / Profile -->
<a href="{{ route('profile.edit') }}" 
   class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition {{ str_starts_with($currentRoute, 'profile') ? 'bg-primary/10 text-primary font-semibold' : 'text-textsec hover:text-textmain hover:bg-gray-100/70' }}">
    <svg class="w-5 h-5 {{ str_starts_with($currentRoute, 'profile') ? 'text-primary' : 'text-gray-400 group-hover:text-textmain' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    <span>Settings</span>
</a>
