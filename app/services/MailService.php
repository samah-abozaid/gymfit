<?php

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class MailService
{
    private $mailtrap; 

    public function __construct()
    {
        $this->mailtrap = MailtrapClient::initSendingEmails(
            apiKey:    $_ENV['MAILTRAP_API_KEY'],
            isBulk:false,
            isSandbox: false,
            inboxId:   (int) $_ENV['MAILTRAP_INBOX_ID']
        );
    }

    public function sendContactEmail(
        string $firstName,
        string $lastName,
        string $email,
        string $subject,
        string $message
    ): bool {
        try {
            $emailMsg = (new MailtrapEmail())
                ->from(new Address($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']))
                ->to(new Address($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']))
                ->replyTo(new Address($email, "$firstName $lastName"))
                ->subject("[$subject] Message de $firstName $lastName")
                ->text("De : $firstName $lastName <$email>\nSujet : $subject\n\n$message")
                ->html("
                    <p><strong>De :</strong> $firstName $lastName &lt;$email&gt;</p>
                    <p><strong>Sujet :</strong> $subject</p>
                    <hr>
                    <p>$message</p>
                ");

            $this->mailtrap->send($emailMsg);
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
}