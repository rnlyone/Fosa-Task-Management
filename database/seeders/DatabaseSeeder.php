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
        // ── Departments ──────────────────────────────────────────
        $deptEM  = Department::create(['name' => 'Event Management']);
        $deptER  = Department::create(['name' => 'External Relations']);
        $deptMC  = Department::create(['name' => 'Media and Creative']);
        $deptRE  = Department::create(['name' => 'Research and Evaluation']);
        $deptLog = Department::create(['name' => 'Logistic']);

        // ── Users ────────────────────────────────────────────────
        $president = User::create([
            'name'     => 'Ima',
            'username' => 'ima',
            'email'    => 'imadias1996@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'president',
            'status'   => 'available',
        ]);

        $vp = User::create([
            'name'     => 'Lin',
            'username' => 'lin',
            'email'    => 'waiyanlynn3142@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'vice_president',
            'status'   => 'available',
        ]);

        $memberData = [
            // Event Management
            ['name' => 'Susan',   'username' => 'susan',   'email' => 'susan.fitriyana@gmail.com',      'dept' => $deptEM],
            ['name' => 'Ayaka',   'username' => 'ayaka',   'email' => 'daidaigomama@gmail.com',         'dept' => $deptEM],
            ['name' => 'Riady',   'username' => 'riady',   'email' => 'arr0103@icloud.com',              'dept' => $deptEM],
            ['name' => 'Octavio', 'username' => 'octavio', 'email' => 'octmolina@hotmail.com',           'dept' => $deptEM],
            // External Relations
            ['name' => 'Yuki',    'username' => 'yuki',    'email' => 'mii_ke2017@yahoo.co.jp',          'dept' => $deptER],
            ['name' => 'Rydll',   'username' => 'rydll',   'email' => 'rydll.pegarido@gmail.com',        'dept' => $deptER],
            ['name' => 'Kyoka',   'username' => 'kyoka',   'email' => 'jumpjump524@gmail.com',           'dept' => $deptER],
            ['name' => 'Qoni',    'username' => 'qoni',    'email' => 'qonii1120@gmail.com',             'dept' => $deptER],
            ['name' => 'Abdel',   'username' => 'abdel',   'email' => 'loverabdel55@gmail.com',          'dept' => $deptER],
            // Media and Creative
            ['name' => 'Ryeona',  'username' => 'ryeona',  'email' => 'cathrillorta5@gmail.com',         'dept' => $deptMC],
            ['name' => 'Auron',   'username' => 'auron',   'email' => 'kopaukedu2022@gmail.com',         'dept' => $deptMC],
            ['name' => 'Akis',    'username' => 'akis',    'email' => 'balqismt00@gmail.com',            'dept' => $deptMC],
            ['name' => 'Momoka',  'username' => 'momoka',  'email' => 'momonga.cho0131@gmail.com',       'dept' => $deptMC],
            ['name' => 'Torikai', 'username' => 'torikai', 'email' => 'torikai.tatsuyuki2005@gmail.com', 'dept' => $deptMC],
            ['name' => 'V',       'username' => 'v',       'email' => 'vyngovutuong@gmail.com',          'dept' => $deptMC],
            // Research and Evaluation
            ['name' => 'Yunice',  'username' => 'yunice',  'email' => 'yunicemalate69@icloud.com',       'dept' => $deptRE],
            ['name' => 'Ajif',    'username' => 'ajif',    'email' => 'ajifyusuf28@gmail.com',           'dept' => $deptRE],
            ['name' => 'Raja',    'username' => 'raja',    'email' => 'ruzmaneman@gmail.com',            'dept' => $deptRE],
            ['name' => 'Mayang',  'username' => 'mayang',  'email' => 'wahyumayangsari@gmail.com',       'dept' => $deptRE],
            ['name' => 'Kotomi',  'username' => 'kotomi',  'email' => 'kotomi.0427.amnos@icloud.com',    'dept' => $deptRE],
            // Logistic
            ['name' => 'Jorge',   'username' => 'jorge',   'email' => 'jorgedelfin2005@gmail.com',       'dept' => $deptLog],
            ['name' => 'Samuel',  'username' => 'samuel',  'email' => 'samuel.owusu26@gmail.com',        'dept' => $deptLog],
            ['name' => 'Maryam',  'username' => 'maryam',  'email' => 'mariam5abdalsattar@gmail.com',    'dept' => $deptLog],
            ['name' => 'Ikram',   'username' => 'ikram',   'email' => 'ikramidris@rocketmail.com',       'dept' => $deptLog],
        ];

        $members = collect($memberData)->map(fn($m) => User::create([
            'name'     => $m['name'],
            'username' => $m['username'],
            'email'    => $m['email'],
            'password' => Hash::make('password'),
            'role'     => 'member',
            'status'   => 'available',
        ]));

        // ── Department assignments ───────────────────────────────
        $deptEM->users()->sync(
            $members->filter(fn($u, $i) => $memberData[$i]['dept'] === $deptEM)->pluck('id')
                ->merge([$president->id, $vp->id])->toArray()
        );
        foreach ([$deptER, $deptMC, $deptRE, $deptLog] as $dept) {
            $dept->users()->sync(
                $members->filter(fn($u, $i) => $memberData[$i]['dept'] === $dept)->pluck('id')->toArray()
            );
        }

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
