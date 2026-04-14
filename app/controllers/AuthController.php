<?php

class AuthController extends AbstractController
{
    // ── Affiche le formulaire Register ──
    public function registerForm(): void
    {
        $subscriptionManager = new SubscriptionManager();
        $subscriptions = $subscriptionManager->findAll();

        $this->render('register', [
            'subscriptions' => $subscriptions,
            'errors'        => [],
            'old'           => []
        ]);
    }

    // ── Traite le formulaire Register ──
    public function register(): void
    {
        $errors = [];
        $old    = $_POST;

        // ── Validation ──
        if (empty($_POST['first_name'])) {
            $errors['first_name'] = 'First name is required.';
        }

        if (empty($_POST['last_name'])) {
            $errors['last_name'] = 'Last name is required.';
        }

        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }

        if (empty($_POST['phone'])) {
            $errors['phone'] = 'Phone is required.';
        }

        if (empty($_POST['password']) || strlen($_POST['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (empty($_POST['id_subscription'])) {
            $errors['id_subscription'] = 'Please choose a plan.';
        }

        // ── Vérifie si email existe déjà ──
        if (empty($errors['email'])) {
            $memberManager = new MemberManager();
            $existing = $memberManager->findByEmail($_POST['email']);
            if ($existing) {
                $errors['email'] = 'This email is already registered.';
            }
        }

        // ── Si erreurs → retour au formulaire ──
        if (!empty($errors)) {
            $subscriptionManager = new SubscriptionManager();
            $subscriptions = $subscriptionManager->findAll();

            $this->render('register', [
                'subscriptions' => $subscriptions,
                'errors'        => $errors,
                'old'           => $old
            ]);
            return;
        }

        // ── Création du membre ──
        $member = new Member(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_BCRYPT),
            $_POST['phone'],
            'active',
            (int) $_POST['id_subscription']
        );

        $memberManager = new MemberManager();
        $memberManager->create($member);

        // ── Redirige vers login ──
        $this->redirect('login');
    }

    // ── Affiche le formulaire Login ──
    public function loginForm(): void
    {
        $this->render('login', [
            'errors' => [],
            'old'    => []
        ]);
    }

    // ── Traite le formulaire Login ──
    public function login(): void
    {
        $errors = [];
        $old    = $_POST;

        // Validation basique
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required.';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = 'Password is required.';
        }

        if (!empty($errors)) {
            $this->render('login', [
                'errors' => $errors,
                'old'    => $old
            ]);
            return;
        }

        // Cherche le membre
        $memberManager = new MemberManager();
        $member = $memberManager->findByEmail($_POST['email']);

        // Vérifie le mot de passe
        if (!$member || !password_verify($_POST['password'], $member->getPassword())) {
            $this->render('login', [
                'errors' => ['login' => 'Invalid email or password.'],
                'old'    => $old
            ]);
            return;
        }

        // Crée la session
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'         => $member->getId(),
            'email'      => $member->getEmail(),
            'first_name' => $member->getFirstName(),
            'last_name'  => $member->getLastName(),
            'role'       => 'member'
        ];

        $this->redirect('home');
    }

    // ── Logout ──
    public function logout(): void
    {
        session_destroy();
        $this->redirect('login');
    }
}