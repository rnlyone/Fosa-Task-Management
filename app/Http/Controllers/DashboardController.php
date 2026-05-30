<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Find the latest active event; fall back to most recent completed
        $event = Event::where('status', 'active')
            ->latest()
            ->first()
            ?? Event::where('status', 'completed')
                ->latest()
                ->first();

        if (!$event) {
            return view('dashboard.no-event');
        }

        $columns = ['backlog' => [], 'todo' => [], 'doing' => [], 'done' => [], 'archive' => []];
        foreach ($columns as $col => $_) {
            $columns[$col] = $event->tasks()
                ->where('column', $col)
                ->with('assignees')
                ->orderBy('position')
                ->get();
        }

        $allEvents   = Event::orderByDesc('id')->get(['id', 'name', 'status']);
        $eventMembers = $event->members()->with('departments')->get();

        $membersData = $eventMembers->mapWithKeys(function ($member) use ($event) {
            $taskCount = $member->tasks()->where('tasks.event_id', $event->id)->count();
            $workload  = $member->workloadScore($event->id);
            $badge = '';
            if ($workload >= 8)       $badge = 'OLM';
            elseif ($taskCount <= 1)  $badge = 'UPM';
            return [$member->id => ['status' => $member->status, 'badge' => $badge]];
        });

        return view('dashboard.index', compact('event', 'columns', 'allEvents', 'eventMembers', 'membersData'));
    }

    public function switchEvent(int $eventId)
    {
        $event = Event::findOrFail($eventId);

        $columns = ['backlog' => [], 'todo' => [], 'doing' => [], 'done' => [], 'archive' => []];
        foreach ($columns as $col => $_) {
            $columns[$col] = $event->tasks()
                ->where('column', $col)
                ->with('assignees')
                ->orderBy('position')
                ->get();
        }

        $allEvents    = Event::orderByDesc('id')->get(['id', 'name', 'status']);
        $eventMembers = $event->members()->with('departments')->get();

        $membersData = $eventMembers->mapWithKeys(function ($member) use ($event) {
            $taskCount = $member->tasks()->where('tasks.event_id', $event->id)->count();
            $workload  = $member->workloadScore($event->id);
            $badge = '';
            if ($workload >= 8)       $badge = 'OLM';
            elseif ($taskCount <= 1)  $badge = 'UPM';
            return [$member->id => ['status' => $member->status, 'badge' => $badge]];
        });

        return view('dashboard.index', compact('event', 'columns', 'allEvents', 'eventMembers', 'membersData'));
    }
}
