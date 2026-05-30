<?php

namespace App\Notifications;

use App\Channels\SmartMailChannel;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventCreated extends Notification
{
    use Queueable;

    public function __construct(public Event $event) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->wantsMailFor('event_created')) {
            $channels[] = SmartMailChannel::class;
        }
        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'event_created',
            'title'      => 'New Event: ' . $this->event->name,
            'body'       => 'You have been added as a member of the event "' . $this->event->name . '".',
            'url'        => route('events.show', $this->event->id),
            'event_id'   => $this->event->id,
            'event_name' => $this->event->name,
        ];
    }

    public function toSmartMail(object $notifiable): array
    {
        $url = route('events.show', $this->event->id);
        return [
            'subject' => '[FOSA] New Event: ' . $this->event->name,
            'html'    => view('emails.notifications.event-created', [
                'event' => $this->event,
                'user'  => $notifiable,
                'url'   => $url,
            ])->render(),
        ];
    }
}
