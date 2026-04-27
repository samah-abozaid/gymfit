<?php

class AdminController extends AbstractController
{
   
    // ── Dashboard ──
    public function index(): void
    {
        $this->requireAdmin();

        $memberManager       = new MemberManager();
        $courseManager       = new CourseManager();
        $subscriptionManager = new SubscriptionManager();

        $this->render('admin/dashboard', [
            'totalMembers'       => $memberManager->countAll(),
            'activeMembers'      => $memberManager->countActive(),
            'totalCourses'       => $courseManager->countAll(),
            'totalSubscriptions' => $subscriptionManager->countAll(),
            'members'            => $memberManager->findAll(),
            'courses'            => $courseManager->findAll(),
            'subscriptions'      => $subscriptionManager->findAll(),
        ]);
    }

    // ══════════════════════════════════
    // CRUD MEMBERS
    // ══════════════════════════════════

    // ── Affiche formulaire création membre ──
    public function createMember(): void
    {
        $this->requireAdmin();

        $subscriptionManager = new SubscriptionManager();

        $this->render('admin/member-create', [
            'subscriptions' => $subscriptionManager->findAll(),
            'errors'        => [],
            'old'           => []
        ]);
    }

    // ── Traite création membre ──
    public function checkCreateMember(): void
    {
       $this->requireAdmin();

    // ── Vérifie CSRF ──
    $tokenManager = new CSRFTokenManager();
    if (!isset($_POST['csrf-token']) || 
        !$tokenManager->validateCSRFToken($_POST['csrf-token']))
    {
        $_SESSION['error-message'] = 'Invalid CSRF token';
        $this->redirect('admin-create-member');
        return;
    }

        $errors = [];
        $old    = $_POST;

        if (empty($_POST['first_name'])) {
            $errors['first_name'] = 'First name is required.';
        }
        if (empty($_POST['last_name'])) {
            $errors['last_name'] = 'Last name is required.';
        }
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }
        if (empty($_POST['password']) || strlen($_POST['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        // Vérifie email unique
        if (empty($errors['email'])) {
            $memberManager = new MemberManager();
            if ($memberManager->findByEmail($_POST['email'])) {
                $errors['email'] = 'This email is already registered.';
            }
        }

        if (!empty($errors)) {
            $subscriptionManager = new SubscriptionManager();
            $this->render('admin/member-create', [
                'subscriptions' => $subscriptionManager->findAll(),
                'errors'        => $errors,
                'old'           => $old
            ]);
            return;
        }

        $member = new Member(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_BCRYPT),
            $_POST['phone'] ?? '',
            $_POST['status'] ?? 'active',
            !empty($_POST['id_subscription']) ? (int)$_POST['id_subscription'] : null
        );

        $memberManager = new MemberManager();
        $memberManager->create($member);

        $this->redirect('admin-members');
    }

    // ── Affiche formulaire modification membre ──
    public function updateMember(): void
    {
       $this->requireAdmin();

        $memberManager       = new MemberManager();
        $subscriptionManager = new SubscriptionManager();

        $member = $memberManager->findOne((int)$_GET['id']);

        if (!$member) {
            $this->redirect('admin-members');
        }

        $this->render('admin/member-update', [
            'member'        => $member,
            'subscriptions' => $subscriptionManager->findAll(),
            'errors'        => [],
            'old'           => []
        ]);
    }

    // ── Traite modification membre ──
    public function checkUpdateMember(): void
    {
       $this->requireAdmin();

    // ── Vérifie CSRF ──
    $tokenManager = new CSRFTokenManager();
    if (!isset($_POST['csrf-token']) || 
        !$tokenManager->validateCSRFToken($_POST['csrf-token']))
    {
        $_SESSION['error-message'] = 'Invalid CSRF token';
        $this->redirect('admin-create-member');
        return;
    }

        $errors = [];
        $old    = $_POST;

        if (empty($_POST['first_name'])) {
            $errors['first_name'] = 'First name is required.';
        }
        if (empty($_POST['last_name'])) {
            $errors['last_name'] = 'Last name is required.';
        }
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }

        if (!empty($errors)) {
            $subscriptionManager = new SubscriptionManager();
            $memberManager       = new MemberManager();
            $this->render('admin/member-update', [
                'member'        => $memberManager->findOne((int)$_POST['id']),
                'subscriptions' => $subscriptionManager->findAll(),
                'errors'        => $errors,
                'old'           => $old
            ]);
            return;
        }

        $memberManager = new MemberManager();
        $member        = $memberManager->findOne((int)$_POST['id']);

        $member->setFirstName($_POST['first_name']);
        $member->setLastName($_POST['last_name']);
        $member->setEmail($_POST['email']);
        $member->setPhone($_POST['phone'] ?? '');
        $member->setStatus($_POST['status']);
        $member->setIdSubscription(
            !empty($_POST['id_subscription']) ? (int)$_POST['id_subscription'] : null
        );

        $memberManager->update($member);

        $this->redirect('admin-members');
    }

    // ── Supprime un membre ──
    public function deleteMember(): void
    {
        $this->requireAdmin();

        $memberManager = new MemberManager();
        $memberManager->delete((int)$_GET['id']);

        $this->redirect('admin-members');
    }

    // ── Liste membres ──
    public function listMembers(): void
    {
        $this->requireAdmin();

        $memberManager = new MemberManager();

        $this->render('admin/members', [
            'members' => $memberManager->findAll()
        ]);
    }

    // ══════════════════════════════════
    // CRUD COURSES
    // ══════════════════════════════════

    // ── Liste cours ──
    public function listCourses(): void
    {
        $this->requireAdmin();

        $courseManager = new CourseManager();

        $this->render('admin/courses', [
            'courses' => $courseManager->findAll()
        ]);
    }

    // ── Affiche formulaire création cours ──
    public function createCourse(): void
    {
        $this->requireAdmin();

        $this->render('admin/course-create', [
            'errors' => [],
            'old'    => []
        ]);
    }

    // ── Traite création cours ──
  public function checkCreateCourse(): void
    {
       $this->requireAdmin();

    // ── Vérifie CSRF ──
    $tokenManager = new CSRFTokenManager();
    if (!isset($_POST['csrf-token']) || 
        !$tokenManager->validateCSRFToken($_POST['csrf-token']))
    {
        $_SESSION['error-message'] = 'Invalid CSRF token';
        $this->redirect('admin-create-member');
        return;
    }

        $errors = [];
        $old    = $_POST;

        if (empty($_POST['name'])) {
            $errors['name'] = 'Course name is required.';
        }
        if (empty($_POST['day'])) {
            $errors['day'] = 'Day is required.';
        }
        if (empty($_POST['start_time'])) {
            $errors['start_time'] = 'Start time is required.';
        }
        if (empty($_POST['end_time'])) {
            $errors['end_time'] = 'End time is required.';
        }

        if (!empty($errors)) {
            $this->render('admin/course-create', [
                'errors' => $errors,
                'old'    => $old
            ]);
            return;
        }

        $course = new Course(
            $_POST['name'],
            $_POST['type']        ?? '',
            $_POST['level']       ?? 'Beginner',
            $_POST['coach']       ?? '',
            $_POST['day'],
            $_POST['start_time'],
            $_POST['end_time'],
            (int)($_POST['max_capacity'] ?? 20),
            $_POST['description'] ?? null
        );

        $courseManager = new CourseManager();
        $courseManager->create($course);

        $this->redirect('admin-courses');
    }

    // ── Affiche formulaire modification cours ──
    public function updateCourse(): void
    {
        $this->requireAdmin();

        $courseManager = new CourseManager();
        $course        = $courseManager->findOne((int)$_GET['id']);

        if (!$course) {
            $this->redirect('admin-courses');
        }

        $this->render('admin/course-update', [
            'course' => $course,
            'errors' => [],
            'old'    => []
        ]);
    }

    // ── Traite modification cours ──
    public function checkUpdateCourse(): void
    {
       $this->requireAdmin();

    // ── Vérifie CSRF ──
    $tokenManager = new CSRFTokenManager();
    if (!isset($_POST['csrf-token']) || 
        !$tokenManager->validateCSRFToken($_POST['csrf-token']))
    {
        $_SESSION['error-message'] = 'Invalid CSRF token';
        $this->redirect('admin-create-member');
        return;
    }

        $errors = [];
        $old    = $_POST;

        if (empty($_POST['name'])) {
            $errors['name'] = 'Course name is required.';
        }

        if (!empty($errors)) {
            $courseManager = new CourseManager();
            $this->render('admin/course-update', [
                'course' => $courseManager->findOne((int)$_POST['id']),
                'errors' => $errors,
                'old'    => $old
            ]);
            return;
        }

        $courseManager = new CourseManager();
        $course        = $courseManager->findOne((int)$_POST['id']);

        $course->setName($_POST['name']);
        $course->setType($_POST['type']        ?? '');
        $course->setLevel($_POST['level']      ?? 'Beginner');
        $course->setCoach($_POST['coach']      ?? '');
        $course->setDay($_POST['day']);
        $course->setStartTime($_POST['start_time']);
        $course->setEndTime($_POST['end_time']);
        $course->setMaxCapacity((int)($_POST['max_capacity'] ?? 20));
     

        $courseManager->update($course);

        $this->redirect('admin-courses');
    }

    // ── Supprime un cours ──
    public function deleteCourse(): void
    {
        $this->requireAdmin();

        $courseManager = new CourseManager();
        $courseManager->delete((int)$_GET['id']);

        $this->redirect('admin-courses');
    }

    // ── Liste abonnements ──
    public function listSubscriptions(): void
    {
        $this->requireAdmin();

        $subscriptionManager = new SubscriptionManager();

        $this->render('admin/subscriptions', [
            'subscriptions' => $subscriptionManager->findAll()
        ]);
    }
}