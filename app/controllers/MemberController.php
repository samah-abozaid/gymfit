<?php


class MemberController extends AbstractController
{
    public function index(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
            $this->redirect('login');
        }

        $memberManager = new MemberManager();
        $member = $memberManager->findOne($_SESSION['user']['id']);

        $subscription = null;
        if ($member->getIdSubscription()) {
            $subscriptionManager = new SubscriptionManager();
            $subscription = $subscriptionManager->findOne($member->getIdSubscription());
        }

        $success = null;
        if (isset($_SESSION['success-message'])) {
            $success = $_SESSION['success-message'];
            unset($_SESSION['success-message']);
        }

        $this->render('member', [
            'member'       => $member,
            'subscription' => $subscription,
            'success'      => $success,
        ]);
    }

    public function editForm(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
            $this->redirect('login');
        }

        $tokenManager = new CSRFTokenManager();
        $tokenManager->generateCSRFToken();

        $memberManager = new MemberManager();
        $member = $memberManager->findOne($_SESSION['user']['id']);

        $error = null;
        if (isset($_SESSION['error-message'])) {
            $error = $_SESSION['error-message'];
            unset($_SESSION['error-message']);
        }

        $this->render('member-edit', [
            'member' => $member,
            'error'  => $error,
        ]);
    }

    public function edit(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
            $this->redirect('login');
        }

        $tokenManager = new CSRFTokenManager();
        if (!isset($_POST['csrf-token']) || !$tokenManager->validateCSRFToken($_POST['csrf-token'])) {
            $_SESSION['error-message'] = 'Invalid CSRF token';
            $this->redirect('member-edit');
            return;
        }

        $firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
        $lastName  = htmlspecialchars(trim($_POST['last_name']  ?? ''));
        $email     = htmlspecialchars(trim($_POST['email']      ?? ''));
        $phone     = htmlspecialchars(trim($_POST['phone']      ?? ''));

        if (empty($firstName) || empty($lastName) || empty($email)) {
            $_SESSION['error-message'] = 'First name, last name and email are required';
            $this->redirect('member-edit');
            return;
        }

        $memberManager = new MemberManager();
        $member = $memberManager->findOne($_SESSION['user']['id']);

        // Vérifie unicité email si changé
        if ($email !== $member->getEmail()) {
            if ($memberManager->findByEmail($email) !== null) {
                $_SESSION['error-message'] = 'This email is already used by another account';
                $this->redirect('member-edit');
                return;
            }
        }

        // Changement de mot de passe (optionnel)
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password']     ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!empty($newPassword)) {
            if (!password_verify($currentPassword, $member->getPassword())) {
                $_SESSION['error-message'] = 'Current password is incorrect';
                $this->redirect('member-edit');
                return;
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $newPassword)) {
                $_SESSION['error-message'] = 'New password must have 8+ characters, one uppercase and one number';
                $this->redirect('member-edit');
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error-message'] = 'Passwords do not match';
                $this->redirect('member-edit');
                return;
            }

            $member->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
            $memberManager->updatePassword($member);
        }

        // Met à jour le profil
        $member->setFirstName($firstName);
        $member->setLastName($lastName);
        $member->setEmail($email);
        $member->setPhone($phone);
        $memberManager->updateProfile($member);

        // Gestion upload avatar (optionnel)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = $this->handleAvatarUpload($_FILES['avatar']);
            if ($avatar === false) {
                $this->redirect('member-edit');
                return;
            }
            // Supprime l'ancienne photo si elle existe
            if ($member->getAvatar()) {
                $old = dirname(__DIR__, 2) . '/public/assets/uploads/avatars/' . $member->getAvatar();
                if (file_exists($old)) unlink($old);
            }
            $memberManager->updateAvatar($member->getId(), $avatar);
        }

        // Met à jour la session
        $_SESSION['user']['first_name'] = $firstName;
        $_SESSION['user']['last_name']  = $lastName;
        $_SESSION['user']['email']      = $email;

        $_SESSION['success-message'] = 'Profile updated successfully!';
        $this->redirect('member');
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
}