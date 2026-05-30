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

        return view('dashboard.index', compact('event', 'columns', 'allEvents', 'eventMembers'));
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

        return view('dashboard.index', compact('event', 'columns', 'allEvents', 'eventMembers'));
    }
}
