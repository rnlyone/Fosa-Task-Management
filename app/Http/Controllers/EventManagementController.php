<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;

class EventManagementController extends Controller
{
    public function show(Event $event)
    {
        $user = auth()->user();

        // Only president, vice president, or the event's own manager may access this page
        if (!$user->isLeadership() && $event->manager_id !== $user->id) {
            abort(403, 'Access denied. Only leadership or the event manager can view this page.');
        }

        $event->load('manager', 'members.departments', 'tasks.assignees');

        $stats        = $event->taskStats();
        $overloaded   = $event->overloadedMembers(8);
        $underperform = $event->underperformingMembers();

        // Per-member workload data for charts
        $memberWorkload = $event->members->map(function (User $member) use ($event) {
            $previousEvent = Event::where('id', '<', $event->id)
                ->where('status', 'completed')
                ->orderByDesc('id')
                ->first();

            $eventIds = [$event->id];
            if ($previousEvent) {
                $eventIds[] = $previousEvent->id;
            }

            $current  = $member->tasks()->where('tasks.event_id', $event->id)->count();
            $previous = $previousEvent
                ? $member->tasks()->where('tasks.event_id', $previousEvent->id)->count()
                : 0;
            $done     = $member->tasks()->whereIn('tasks.event_id', $eventIds)->where('column', 'done')->count();

            return [
                'id'       => $member->id,
                'name'     => $member->name,
                'current'  => $current,
                'previous' => $previous,
                'total'    => $current + $previous,
                'done'     => $done,
                'avatar'   => $member->avatar_url,
                'status'   => $member->status,
            ];
        })->sortByDesc('total')->values();

        // Member status overview
        $statusCounts = $event->members
            ->groupBy('status')
            ->map->count();

        return view('event-management.show', compact(
            'event',
            'stats',
            'overloaded',
            'underperform',
            'memberWorkload',
            'statusCounts',
        ));
    }
}
