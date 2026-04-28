<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['MAIL_USERNAME'];
        $this->mail->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = (int) $_ENV['MAIL_PORT'];
        $this->mail->CharSet    = 'UTF-8';
    }

    public function sendContactEmail(
        string $firstName,
        string $lastName,
        string $email,
        string $subject,
        string $message
    ): bool {
        try {
            $this->mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
            $this->mail->addAddress($_ENV['MAIL_TO'], $_ENV['MAIL_TO_NAME']);
            $this->mail->addReplyTo($email, "$firstName $lastName");

            $this->mail->isHTML(true);
            $this->mail->Subject = "[$subject] Message de $firstName $lastName";
            $this->mail->Body    = "
                <p><strong>De :</strong> $firstName $lastName &lt;$email&gt;</p>
                <p><strong>Sujet :</strong> $subject</p>
                <hr>
                <p>$message</p>
            ";
            $this->mail->AltBody = "De : $firstName $lastName <$email>\nSujet : $subject\n\n$message";

            $this->mail->send();
            return true;

        } catch (Exception $e) {
            $_SESSION['error-message'] = 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}