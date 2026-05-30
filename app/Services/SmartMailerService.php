<?php

namespace App\Services;

use App\Models\MailerAccount;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class SmartMailerService
{
    /**
     * Send an email through active mailer accounts in priority order.
     * Falls back to the next account if one fails.
     */
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $accounts = MailerAccount::activeOrdered()->get();

        if ($accounts->isEmpty()) {
            Log::warning('SmartMailer: No active mailer accounts configured.');
            return false;
        }

        foreach ($accounts as $account) {
            try {
                $tls = match ($account->encryption) {
                    'ssl'  => true,
                    'tls'  => false,
                    default => false,
                };

                $transport = new EsmtpTransport(
                    $account->host,
                    $account->port,
                    $tls,
                );
                $transport->setUsername($account->username);
                $transport->setPassword($account->password);

                if ($account->encryption === 'tls') {
                    $transport->getStream()->setStreamOptions([
                        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
                    ]);
                }

                $mailer = new SymfonyMailer($transport);

                $email = (new Email())
                    ->from(new Address($account->from_address, $account->from_name))
                    ->to(new Address($toEmail, $toName))
                    ->subject($subject)
                    ->html($htmlBody);

                $mailer->send($email);

                Log::info("SmartMailer: Sent via [{$account->name}] to {$toEmail}");
                return true;

            } catch (\Throwable $e) {
                Log::warning("SmartMailer: Account [{$account->name}] failed — {$e->getMessage()}. Trying next.");
            }
        }

        Log::error("SmartMailer: All mailer accounts failed for {$toEmail}.");
        return false;
    }
}
