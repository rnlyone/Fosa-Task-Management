<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventCreated as EventCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('manager')->latest()->paginate(12);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'username', 'role']);
        return view('events.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'manager_id'            => ['required', 'exists:users,id'],
            'event_date'            => ['required', 'date'],
            'start_preparing_date'  => ['required', 'date', 'before_or_equal:event_date'],
            'members'               => ['nullable', 'array'],
            'members.*'             => ['exists:users,id'],
            'status'                => ['required', 'in:preparation,active,completed'],
        ]);

        $event = Event::create([
            'name'                 => $data['name'],
            'manager_id'           => $data['manager_id'],
            'event_date'           => $data['event_date'],
            'start_preparing_date' => $data['start_preparing_date'],
            'status'               => $data['status'],
        ]);

        $members = collect($data['members'] ?? []);
        // Always include the manager as a member
        $members->push($data['manager_id']);
        $memberIds = $members->unique()->values()->toArray();
        $event->members()->sync($memberIds);

        // Reset all event members' status to available
        User::whereIn('id', $memberIds)->update(['status' => 'available']);

        // Auto-create evaluation form: opens at prep start, closes 10 days after event date
        \App\Models\Evaluation::create([
            'event_id'  => $event->id,
            'opens_at'  => $event->start_preparing_date,
            'closes_at' => $event->event_date?->addDays(10),
        ]);

        // Notify all event members about the new event
        $members = User::whereIn('id', $memberIds)->get();
        Notification::send($members, new EventCreatedNotification($event));

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load('manager', 'members.departments', 'tasks');
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $users = User::orderBy('name')->get(['id', 'name', 'username', 'role']);
        $event->load('members');
        return view('events.edit', compact('event', 'users'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'manager_id'            => ['required', 'exists:users,id'],
            'event_date'            => ['required', 'date'],
            'start_preparing_date'  => ['required', 'date', 'before_or_equal:event_date'],
            'members'               => ['nullable', 'array'],
            'members.*'             => ['exists:users,id'],
            'status'                => ['required', 'in:preparation,active,completed'],
        ]);

        $event->update([
            'name'                 => $data['name'],
            'manager_id'           => $data['manager_id'],
            'event_date'           => $data['event_date'],
            'start_preparing_date' => $data['start_preparing_date'],
            'status'               => $data['status'],
        ]);

        $members = collect($data['members'] ?? []);
        $members->push($data['manager_id']);
        $event->members()->sync($members->unique()->values()->toArray());

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted.');
    }
}
