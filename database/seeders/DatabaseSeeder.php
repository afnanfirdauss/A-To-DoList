<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        $today = Carbon::today();

        // 1. Create Core Demo Users
        $owner = User::create([
            'name' => 'Budi Santoso',
            'email' => 'demo@taskflow.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $member1 = User::create([
            'name' => 'Siti Rahma',
            'email' => 'member@taskflow.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $member2 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'dev@taskflow.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // 2. Categories for Owner
        $catDev = Category::create(['user_id' => $owner->id, 'name' => 'Development']);
        $catDesign = Category::create(['user_id' => $owner->id, 'name' => 'Design UI/UX']);
        $catBug = Category::create(['user_id' => $owner->id, 'name' => 'Bugfix']);
        $catDocs = Category::create(['user_id' => $owner->id, 'name' => 'Dokumentasi']);
        Category::create(['user_id' => $member1->id, 'name' => 'Frontend Research']);

        // 3. Projects
        $projPortal = Project::create([
            'user_id' => $owner->id,
            'name' => 'Redesign Web Portal TaskFlow',
            'description' => 'Pembaruan arsitektur antarmuka dashboard, sistem navigasi modern, dan peningkatan performa frontend.',
            'color' => '#2F855A',
        ]);
        $projPortal->members()->attach([
            $member1->id => ['role' => 'member'],
            $member2->id => ['role' => 'member'],
        ]);

        $projApi = Project::create([
            'user_id' => $owner->id,
            'name' => 'Mobile App API & Integrasi',
            'description' => 'Penyusunan endpoint RESTful JSON, push notifications, dan dokumentasi OpenAPI.',
            'color' => '#2563EB',
        ]);
        $projApi->members()->attach([
            $member2->id => ['role' => 'member'],
        ]);

        $projMarketing = Project::create([
            'user_id' => $member1->id,
            'name' => 'Riset Pengalaman Pengguna Q4',
            'description' => 'Wawancara pengguna, analisis feedback komunitas, dan pembuatan prototype wireframe.',
            'color' => '#D97706',
        ]);
        $projMarketing->members()->attach([
            $owner->id => ['role' => 'member'],
        ]);

        // 4. Tasks Creation with Realistic Dates for Charts, Kanban & Calendar

        // Task 1: Completed Task 3 days ago
        $t1 = Task::create([
            'project_id' => $projPortal->id,
            'category_id' => $catDesign->id,
            'created_by' => $owner->id,
            'assigned_to' => $member1->id,
            'title' => 'Rancang Design System & Palet Warna Inter',
            'description' => 'Tentukan token Tailwind CSS, typography Inter, kartu dengan sudut membulat dan white-space optimal.',
            'status' => 'done',
            'priority' => 'high',
            'start_date' => $today->copy()->subDays(6),
            'due_date' => $today->copy()->subDays(3),
            'completed_at' => $today->copy()->subDays(3)->setHour(14),
            'created_at' => $today->copy()->subDays(6),
        ]);

        // Task 2: Completed Task 2 days ago
        $t2 = Task::create([
            'project_id' => $projPortal->id,
            'category_id' => $catDev->id,
            'created_by' => $owner->id,
            'assigned_to' => $owner->id,
            'title' => 'Scaffolding Laravel 11 & Breeze Blade Monolitik',
            'description' => 'Konfigurasi environment lokal, Tailwind CSS Vite build, dan autentikasi dasar.',
            'status' => 'done',
            'priority' => 'high',
            'start_date' => $today->copy()->subDays(5),
            'due_date' => $today->copy()->subDays(2),
            'completed_at' => $today->copy()->subDays(2)->setHour(16),
            'created_at' => $today->copy()->subDays(5),
        ]);

        // Task 3: Completed Task Yesterday
        $t3 = Task::create([
            'project_id' => $projApi->id,
            'category_id' => $catDev->id,
            'created_by' => $owner->id,
            'assigned_to' => $member2->id,
            'title' => 'Desain ERD Database & Relasi Eloquent',
            'description' => 'Tabel users, projects, project_members, categories, tasks, comments, notifications, dan task_activities.',
            'status' => 'done',
            'priority' => 'high',
            'start_date' => $today->copy()->subDays(4),
            'due_date' => $today->copy()->subDays(1),
            'completed_at' => $today->copy()->subDays(1)->setHour(11),
            'created_at' => $today->copy()->subDays(4),
        ]);

        // Task 4: In Progress Task (Due Today)
        $t4 = Task::create([
            'project_id' => $projPortal->id,
            'category_id' => $catDev->id,
            'created_by' => $owner->id,
            'assigned_to' => $member1->id,
            'title' => 'Integrasi Drag-and-Drop SortableJS pada Kanban Board',
            'description' => 'Pastikan pemindahan kartu memperbarui status di database melalui AJAX PATCH dan memberikan umpan balik toast.',
            'status' => 'in_progress',
            'priority' => 'high',
            'start_date' => $today->copy()->subDays(1),
            'due_date' => $today->copy(),
            'created_at' => $today->copy()->subDays(1),
        ]);

        // Task 5: In Progress Task (Due Tomorrow - Approaching Deadline)
        $t5 = Task::create([
            'project_id' => $projPortal->id,
            'category_id' => $catDev->id,
            'created_by' => $owner->id,
            'assigned_to' => $owner->id,
            'title' => 'Visualisasi Produktivitas Chart.js pada Dashboard',
            'description' => 'Tampilkan perbandingan tugas selesai vs dibuat per hari dan proporsi distribusi prioritas tugas.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'start_date' => $today->copy(),
            'due_date' => $today->copy()->addDay(),
            'created_at' => $today->copy(),
        ]);

        // Task 6: Overdue Task (Deadline passed, status still in_progress)
        $t6 = Task::create([
            'project_id' => $projApi->id,
            'category_id' => $catBug->id,
            'created_by' => $owner->id,
            'assigned_to' => $member2->id,
            'title' => 'Perbaiki Bug Validasi Rentang Tanggal Mulai dan Deadline',
            'description' => 'Pastikan sistem menolak input jika tanggal mulai melampaui tanggal deadline yang ditentukan.',
            'status' => 'in_progress',
            'priority' => 'high',
            'start_date' => $today->copy()->subDays(5),
            'due_date' => $today->copy()->subDays(2),
            'created_at' => $today->copy()->subDays(5),
        ]);

        // Task 7: To Do Task (Due Next Week)
        $t7 = Task::create([
            'project_id' => $projPortal->id,
            'category_id' => $catDev->id,
            'created_by' => $owner->id,
            'assigned_to' => $member1->id,
            'title' => 'Sinkronisasi FullCalendar Feed Endpoint',
            'description' => 'Buat endpoint JSON calendar events dengan filter proyek dan pewarnaan event berdasarkan status.',
            'status' => 'todo',
            'priority' => 'medium',
            'start_date' => $today->copy()->addDays(2),
            'due_date' => $today->copy()->addDays(5),
            'created_at' => $today->copy(),
        ]);

        // Task 8: To Do Task (Low Priority, Personal / No Project)
        $t8 = Task::create([
            'project_id' => null,
            'category_id' => $catDocs->id,
            'created_by' => $owner->id,
            'assigned_to' => $owner->id,
            'title' => 'Perbarui Catatan Portofolio & README.md TaskFlow',
            'description' => 'Tuliskan dokumentasi instalasi, konfigurasi scheduler cron, dan diagram arsitektur.',
            'status' => 'todo',
            'priority' => 'low',
            'start_date' => null,
            'due_date' => $today->copy()->addDays(7),
            'created_at' => $today->copy(),
        ]);

        // Task 9: To Do Task in Marketing Project (Owner is Siti, assigned to Budi)
        $t9 = Task::create([
            'project_id' => $projMarketing->id,
            'category_id' => null,
            'created_by' => $member1->id,
            'assigned_to' => $owner->id,
            'title' => 'Review Laporan Kebutuhan Pengguna Q4',
            'description' => 'Berikan catatan analisis dan persetujuan fitur pada backlog produk.',
            'status' => 'todo',
            'priority' => 'medium',
            'start_date' => $today->copy(),
            'due_date' => $today->copy()->addDays(3),
            'created_at' => $today->copy(),
        ]);

        // 5. Activity Log Seed
        TaskActivity::create([
            'task_id' => $t4->id,
            'user_id' => $owner->id,
            'action' => 'created',
            'old_value' => null,
            'new_value' => $t4->title,
            'created_at' => $today->copy()->subDays(1),
        ]);
        TaskActivity::create([
            'task_id' => $t4->id,
            'user_id' => $member1->id,
            'action' => 'status_updated',
            'old_value' => 'todo',
            'new_value' => 'in_progress',
            'created_at' => $today->copy()->subHours(4),
        ]);
        TaskActivity::create([
            'task_id' => $t6->id,
            'user_id' => $owner->id,
            'action' => 'priority_updated',
            'old_value' => 'medium',
            'new_value' => 'high',
            'created_at' => $today->copy()->subDays(2),
        ]);

        // 6. Comments Seed
        Comment::create([
            'task_id' => $t4->id,
            'user_id' => $member1->id,
            'body' => 'SortableJS sudah terpasang via Vite. Saat ini sedang menghubungkan event onEnd dengan endpoint PATCH.',
            'created_at' => $today->copy()->subHours(3),
        ]);
        Comment::create([
            'task_id' => $t4->id,
            'user_id' => $owner->id,
            'body' => 'Bagus sekali Siti, jangan lupa pastikan feedback toast muncul ketika status berhasil diperbarui.',
            'created_at' => $today->copy()->subHours(2),
        ]);

        // 7. In-App Notifications Seed
        Notification::create([
            'user_id' => $owner->id,
            'task_id' => $t6->id,
            'type' => 'overdue',
            'message' => "Tugas '{$t6->title}' telah melewati batas deadline ({$t6->due_date->format('d M Y')}). Segera selesaikan!",
            'created_at' => $today->copy()->subHours(5),
            'read_at' => null,
        ]);
        Notification::create([
            'user_id' => $owner->id,
            'task_id' => $t4->id,
            'type' => 'deadline_approaching',
            'message' => "Pengingat: Tugas '{$t4->title}' akan jatuh tempo HARI INI.",
            'created_at' => $today->copy()->subHours(2),
            'read_at' => null,
        ]);
        Notification::create([
            'user_id' => $owner->id,
            'task_id' => $t9->id,
            'type' => 'task_assigned',
            'message' => "Anda telah ditugaskan pada tugas: '{$t9->title}' oleh Siti Rahma.",
            'created_at' => $today->copy()->subHours(1),
            'read_at' => null,
        ]);
    }
}
