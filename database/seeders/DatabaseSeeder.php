<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────────────
        $president = User::create([
            'name'     => 'Ahmad Fauzan',
            'username' => 'president',
            'email'    => 'president@fosa.id',
            'password' => Hash::make('password'),
            'role'     => 'president',
            'status'   => 'available',
        ]);

        $vp = User::create([
            'name'     => 'Siti Rahayu',
            'username' => 'vicepresident',
            'email'    => 'vp@fosa.id',
            'password' => Hash::make('password'),
            'role'     => 'vice_president',
            'status'   => 'available',
        ]);

        $memberData = [
            ['name' => 'Budi Santoso',   'username' => 'budi',   'email' => 'budi@fosa.id',   'status' => 'free'],
            ['name' => 'Citra Dewi',     'username' => 'citra',  'email' => 'citra@fosa.id',  'status' => 'available'],
            ['name' => 'Dian Pratama',   'username' => 'dian',   'email' => 'dian@fosa.id',   'status' => 'busy'],
            ['name' => 'Eko Wahyudi',    'username' => 'eko',    'email' => 'eko@fosa.id',    'status' => 'free'],
            ['name' => 'Fitri Handayani','username' => 'fitri',  'email' => 'fitri@fosa.id',  'status' => 'available'],
            ['name' => 'Gilang Ramadan', 'username' => 'gilang', 'email' => 'gilang@fosa.id', 'status' => 'busy'],
            ['name' => 'Hana Pertiwi',   'username' => 'hana',   'email' => 'hana@fosa.id',   'status' => 'very_busy'],
            ['name' => 'Irfan Malik',    'username' => 'irfan',  'email' => 'irfan@fosa.id',  'status' => 'available'],
        ];

        $members = collect($memberData)->map(fn($m) => User::create(array_merge($m, [
            'password' => Hash::make('password'),
            'role'     => 'member',
        ])));

        // ── Departments ──────────────────────────────────────────
        $deptAccara = Department::create(['name' => 'Acara', 'description' => 'Divisi Acara dan Hiburan']);
        $deptHumas  = Department::create(['name' => 'Humas', 'description' => 'Divisi Hubungan Masyarakat']);
        $deptLogistik = Department::create(['name' => 'Logistik', 'description' => 'Divisi Perlengkapan dan Logistik']);

        $deptAccara->users()->sync([$members[0]->id, $members[1]->id, $members[2]->id, $vp->id]);
        $deptHumas->users()->sync([$members[3]->id, $members[4]->id]);
        $deptLogistik->users()->sync([$members[5]->id, $members[6]->id, $members[7]->id]);

        // ── Previous completed event ─────────────────────────────
        $prevEvent = Event::create([
            'name'                 => 'FOSA Night 2024',
            'description'          => 'Malam Keakraban Organisasi FOSA 2024',
            'manager_id'           => $vp->id,
            'event_date'           => now()->subMonths(3),
            'start_preparing_date' => now()->subMonths(5),
            'status'               => 'completed',
        ]);
        $allUsers = $members->concat([$president, $vp]);
        $prevEvent->members()->sync($allUsers->pluck('id'));

        // Some tasks on the previous event (to test overload calculation)
        foreach ($members->take(3) as $m) {
            Task::create([
                'event_id'   => $prevEvent->id,
                'created_by' => $president->id,
                'title'      => 'Previous task for ' . $m->name,
                'column'     => 'done',
                'priority'   => 'medium',
            ])->assignees()->attach($m->id);
        }

        // ── Active event ─────────────────────────────────────────
        $event = Event::create([
            'name'                 => 'FOSA Festival 2025',
            'description'          => 'Festival Seni dan Budaya Tahunan FOSA 2025',
            'manager_id'           => $vp->id,
            'event_date'           => now()->addMonths(2),
            'start_preparing_date' => now()->subWeeks(2),
            'status'               => 'active',
        ]);
        $event->members()->sync($allUsers->pluck('id'));

        // ── Tasks ─────────────────────────────────────────────────
        $taskDefs = [
            // backlog
            ['title' => 'Riset venue acara',       'column' => 'backlog', 'priority' => 'high',   'assignee' => $members[0]],
            ['title' => 'Buat proposal sponsorship','column' => 'backlog', 'priority' => 'medium', 'assignee' => $members[1]],
            // todo
            ['title' => 'Desain poster acara',     'column' => 'todo',    'priority' => 'high',   'assignee' => $members[2]],
            ['title' => 'Hubungi calon sponsor',   'column' => 'todo',    'priority' => 'medium', 'assignee' => $members[3]],
            ['title' => 'Susun jadwal kegiatan',   'column' => 'todo',    'priority' => 'low',    'assignee' => $members[4]],
            // doing
            ['title' => 'Persiapan dekorasi',      'column' => 'doing',   'priority' => 'high',   'assignee' => $members[5],
             'deadline' => now()->addDays(7)],
            ['title' => 'Koordinasi pengisi acara', 'column' => 'doing',  'priority' => 'medium', 'assignee' => $members[6],
             'deadline' => now()->addDays(10)],
            ['title' => 'Booking catering',        'column' => 'doing',   'priority' => 'medium', 'assignee' => $members[7],
             'deadline' => now()->addDays(5)],
            // done
            ['title' => 'Kick-off meeting',        'column' => 'done',    'priority' => 'medium', 'assignee' => $president],
            ['title' => 'Pembentukan panitia',     'column' => 'done',    'priority' => 'low',    'assignee' => $vp],
            // overdue task to demo indicator
            ['title' => 'Konfirmasi perizinan',    'column' => 'doing',   'priority' => 'high',   'assignee' => $members[0],
             'deadline' => now()->subDays(3)],
        ];

        foreach ($taskDefs as $i => $def) {
            $task = Task::create([
                'event_id'      => $event->id,
                'created_by'    => $president->id,
                'title'         => $def['title'],
                'column'        => $def['column'],
                'priority'      => $def['priority'],
                'deadline_date' => $def['deadline'] ?? null,
                'position'      => $i,
            ]);
            $task->assignees()->attach($def['assignee']->id);
        }
    }
}
