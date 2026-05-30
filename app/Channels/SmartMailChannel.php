<?php

namespace App\Channels;

use App\Services\SmartMailerService;
use Illuminate\Notifications\Notification;

class SmartMailChannel
{
    public function __construct(private SmartMailerService $mailer) {}

    public function send(mixed $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSmartMail')) {
            return;
        }

        $email = $notifiable->email ?? null;
        $name  = $notifiable->name  ?? 'User';

        if (!$email) {
            return;
        }

        ['subject' => $subject, 'html' => $html] = $notification->toSmartMail($notifiable);

        $this->mailer->send($email, $name, $subject, $html);
    }
}
