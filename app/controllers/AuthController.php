<?php

class AuthController extends AbstractController
{
    // ── Affiche Login ──
    public function loginForm(): void
    {
        $tokenManager = new CSRFTokenManager();
        $tokenManager->generateCSRFToken();

        $errors = [];
        if (isset($_SESSION['error-message'])) {
            $errors['login'] = $_SESSION['error-message'];
            unset($_SESSION['error-message']);
        }

        $this->render('login', ['errors' => $errors]);
    }

    // ── Traite Login ──
    public function login(): void
    {
        if (isset($_POST['email']) && isset($_POST['password']))
        {
            $tokenManager = new CSRFTokenManager();

            if (isset($_POST['csrf-token']) && $tokenManager->validateCSRFToken($_POST['csrf-token']))
            {
                $adminManager = new AdminManager();
                $admin = $adminManager->findByEmail($_POST['email']);

                if ($admin !== null)
                {
                    if (password_verify($_POST['password'], $admin->getPassword()))
                    {
                        session_regenerate_id(true);
                        $_SESSION['user'] = [
                            'id'         => $admin->getId(),
                            'email'      => $admin->getEmail(),
                            'first_name' => $admin->getName(),
                            'last_name'  => '',
                            'role'       => 'admin'
                        ];

                        unset($_SESSION['error-message']);
                        $this->redirect('admin');
                    }
                    else
                    {
                        $_SESSION['error-message'] = 'Invalid login information';
                        $this->redirect('login');
                    }
                }
                else
                {
                    $memberManager = new MemberManager();
                    $member = $memberManager->findByEmail($_POST['email']);

                    if ($member !== null)
                    {
                        if (password_verify($_POST['password'], $member->getPassword()))
                        {
                            session_regenerate_id(true);
                            $_SESSION['user'] = [
                                'id'         => $member->getId(),
                                'email'      => $member->getEmail(),
                                'first_name' => $member->getFirstName(),
                                'last_name'  => $member->getLastName(),
                                'role'       => 'member'
                            ];

                            unset($_SESSION['error-message']);
                            $this->redirect('member');
                        }
                        else
                        {
                            $_SESSION['error-message'] = 'Invalid login information';
                            $this->redirect('login');
                        }
                    }
                    else
                    {
                        $_SESSION['error-message'] = 'Invalid login information';
                        $this->redirect('login');
                    }
                }
            }
            else
            {
                $_SESSION['error-message'] = 'Invalid CSRF token';
                $this->redirect('login');
            }
        }
        else
        {
            $_SESSION['error-message'] = 'Missing fields';
            $this->redirect('login');
        }
    }

    // ── Affiche Register ──
    public function registerForm(): void
    {
        $tokenManager = new CSRFTokenManager();
        $tokenManager->generateCSRFToken();

        $errors = [];
        if (isset($_SESSION['error-message'])) {
            $errors['register'] = $_SESSION['error-message'];
            unset($_SESSION['error-message']);
        }

        $subscriptionManager = new SubscriptionManager();

        $this->render('register', [
            'subscriptions' => $subscriptionManager->findAll(),
            'errors'        => $errors,
        ]);
    }

    // ── Traite Register ──
    public function register(): void
    {
        if (isset($_POST['first_name']) &&
            isset($_POST['last_name']) &&
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['confirm_password']))
        {
            $tokenManager = new CSRFTokenManager();

            if (isset($_POST['csrf-token']) && $tokenManager->validateCSRFToken($_POST['csrf-token']))
            {
                if ($_POST['password'] === $_POST['confirm_password'])
                {
                    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
                    if (preg_match($password_pattern, $_POST['password']))
                    {
                        $memberManager = new MemberManager();
                        $existing = $memberManager->findByEmail($_POST['email']);

                        if ($existing === null)
                        {
                            // ── Gestion upload avatar (optionnel) ──
                            $avatar = null;
                            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                                $avatar = $this->handleAvatarUpload($_FILES['avatar']);
                                if ($avatar === false) {
                                    $this->redirect('register');
                                    return;
                                }
                            }

                            $member = new Member(
                                htmlspecialchars($_POST['first_name']),
                                htmlspecialchars($_POST['last_name']),
                                htmlspecialchars($_POST['email']),
                                password_hash($_POST['password'], PASSWORD_BCRYPT),
                                htmlspecialchars($_POST['phone'] ?? ''),
                                'active',
                                !empty($_POST['id_subscription']) ? (int)$_POST['id_subscription'] : null,
                                null,
                                null,
                                $avatar
                            );

                            $memberManager->create($member);

                            // ── Auto-login + message de bienvenue ──
                            $newMember = $memberManager->findByEmail($_POST['email']);
                            session_regenerate_id(true);
                            $_SESSION['user'] = [
                                'id'         => $newMember->getId(),
                                'email'      => $newMember->getEmail(),
                                'first_name' => $newMember->getFirstName(),
                                'last_name'  => $newMember->getLastName(),
                                'role'       => 'member',
                            ];

                            unset($_SESSION['error-message']);
                            $_SESSION['success-message'] = 'Welcome to GymFit, ' . $newMember->getFirstName() . '! Your account has been created successfully.';
                            $this->redirect('member');
                        }
                        else
                        {
                            $_SESSION['error-message'] = 'This email is already registered';
                            $this->redirect('register');
                        }
                    }
                    else
                    {
                        $_SESSION['error-message'] = 'Password must have 8+ characters, uppercase, number and special character';
                        $this->redirect('register');
                    }
                }
                else
                {
                    $_SESSION['error-message'] = 'Passwords do not match';
                    $this->redirect('register');
                }
            }
            else
            {
                $_SESSION['error-message'] = 'Invalid CSRF token';
                $this->redirect('register');
            }
        }
        else
        {
            $_SESSION['error-message'] = 'Missing fields';
            $this->redirect('register');
        }
    }

    private function handleAvatarUpload(array $file): string|false
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 2 * 1024 * 1024;

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $_SESSION['error-message'] = 'Only JPG, PNG, WebP or GIF images are allowed';
            return false;
        }
        if ($file['size'] > $maxSize) {
            $_SESSION['error-message'] = 'Image must be smaller than 2 MB';
            return false;
        }

        $ext = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'jpg',
        };

        $filename  = 'avatar_' . uniqid() . '.' . $ext;
        $uploadDir = dirname(__DIR__, 2) . '/public/assets/uploads/avatars/';
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        return $filename;
    }

    // ── Logout ──
    public function logout(): void
    {
        session_destroy();
        $this->redirect('login');
    }
}