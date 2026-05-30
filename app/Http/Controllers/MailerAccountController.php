<?php

namespace App\Http\Controllers;

use App\Models\MailerAccount;
use Illuminate\Http\Request;

class MailerAccountController extends Controller
{
    public function index()
    {
        $accounts = MailerAccount::orderBy('priority')->orderBy('id')->get();
        return view('mailer-accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'host'         => ['required', 'string', 'max:255'],
            'port'         => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption'   => ['required', 'in:tls,ssl,none'],
            'username'     => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name'    => ['required', 'string', 'max:100'],
            'priority'     => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['priority']  = $data['priority'] ?? 0;

        MailerAccount::create($data);

        return back()->with('success', 'Mailer account added.');
    }

    public function update(Request $request, MailerAccount $mailerAccount)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'host'         => ['required', 'string', 'max:255'],
            'port'         => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption'   => ['required', 'in:tls,ssl,none'],
            'username'     => ['required', 'string', 'max:255'],
            'password'     => ['nullable', 'string'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name'    => ['required', 'string', 'max:100'],
            'priority'     => ['nullable', 'integer', 'min:0'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['priority']  = $data['priority'] ?? 0;

        // Only update password if provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $mailerAccount->update($data);

        return back()->with('success', 'Mailer account updated.');
    }

    public function destroy(MailerAccount $mailerAccount)
    {
        $mailerAccount->delete();
        return back()->with('success', 'Mailer account deleted.');
    }

    public function test(MailerAccount $mailerAccount)
    {
        /** @var \App\Services\SmartMailerService $mailer */
        $mailer = app(\App\Services\SmartMailerService::class);

        $user = auth()->user();

        // Temporarily test only this specific account by using it directly
        $html = view('emails.notifications.task-assigned', [
            'task' => (object)[
                'title'         => 'Test Task',
                'priority'      => 'medium',
                'deadline_date' => null,
                'description'   => 'This is a test email to verify your mailer configuration.',
                'event'         => (object)['name' => 'Test Event'],
            ],
            'user' => $user,
            'url'  => route('mailer-accounts.index'),
        ])->render();

        try {
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $mailerAccount->host,
                $mailerAccount->port,
                $mailerAccount->encryption === 'ssl',
            );
            $transport->setUsername($mailerAccount->username);
            $transport->setPassword($mailerAccount->password);

            $symfonyMailer = new \Symfony\Component\Mailer\Mailer($transport);
            $email = (new \Symfony\Component\Mime\Email())
                ->from(new \Symfony\Component\Mime\Address($mailerAccount->from_address, $mailerAccount->from_name))
                ->to(new \Symfony\Component\Mime\Address($user->email, $user->name))
                ->subject('[FOSA] Test Email from ' . $mailerAccount->name)
                ->html($html);

            $symfonyMailer->send($email);

            return back()->with('success', "Test email sent to {$user->email} via [{$mailerAccount->name}].");
        } catch (\Throwable $e) {
            return back()->with('error', "Test failed: " . $e->getMessage());
        }
    }
}
