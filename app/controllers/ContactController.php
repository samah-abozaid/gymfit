<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends AbstractController
{
    public function index(): void
    {
        $this->render('contact', [
            'title'   => 'GymFit — Contact',
            'success' => false,
            'errors'  => [],
            'old'     => []
        ]);
    }

    public function send(): void
    {
        if (isset($_POST['first_name']) &&
            isset($_POST['last_name']) &&
            isset($_POST['email']) &&
            isset($_POST['message']))
        {
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST['csrf-token']) &&
                $tokenManager->validateCSRFToken($_POST['csrf-token']))
            {
                $errors = [];

                if (empty($_POST['first_name'])) {
                    $errors['first_name'] = 'First name is required.';
                }
                if (empty($_POST['last_name'])) {
                    $errors['last_name'] = 'Last name is required.';
                }
                if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Valid email is required.';
                }
                if (empty($_POST['message'])) {
                    $errors['message'] = 'Message is required.';
                }

                if (!empty($errors)) {
                    $this->render('contact', [
                        'title'   => 'GymFit — Contact',
                        'success' => false,
                        'errors'  => $errors,
                        'old'     => $_POST
                    ]);
                    return;
                }

                // ── Données nettoyées ──────────────────────────
                $firstName = htmlspecialchars($_POST['first_name']);
                $lastName  = htmlspecialchars($_POST['last_name']);
                $email     = htmlspecialchars($_POST['email']);
                $subject   = htmlspecialchars($_POST['subject'] ?? 'other');
                $message   = htmlspecialchars($_POST['message']);

                // ── Envoi via MailService ──────────────────────
                $mailService = new MailService();
                $sent = $mailService->sendContactEmail(
                    $firstName,
                    $lastName,
                    $email,
                    $subject,
                    $message
                );

                if ($sent) {
                    $this->render('contact', [
                        'title'   => 'GymFit — Contact',
                        'success' => true,
                        'errors'  => [],
                        'old'     => []
                    ]);
                } else {
                    $_SESSION['error-message'] = 'Erreur lors de l\'envoi du message.';
                    $this->redirect('contact');
                }

            } else {
                $_SESSION['error-message'] = 'Invalid CSRF token';
                $this->redirect('contact');
            }
        } else {
            $_SESSION['error-message'] = 'Missing fields';
            $this->redirect('contact');
        }
    }
}