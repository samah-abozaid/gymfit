<?php
// Abstract classes
require_once dirname(__DIR__) . '/app/managers/AbstractManager.php';
require_once dirname(__DIR__) . '/app/controllers/AbstractController.php';

// Models
require_once dirname(__DIR__) . '/app/models/Member.php';
require_once dirname(__DIR__) . '/app/models/Course.php';
require_once dirname(__DIR__) . '/app/models/Subscription.php';
require_once dirname(__DIR__) . '/app/models/Admin.php';

// Managers
require_once dirname(__DIR__) . '/app/managers/MemberManager.php';
require_once dirname(__DIR__) . '/app/managers/CourseManager.php';
require_once dirname(__DIR__) . '/app/managers/SubscriptionManager.php';
require_once dirname(__DIR__) . '/app/managers/AdminManager.php';

// Controllers
require_once dirname(__DIR__) . '/app/controllers/HomeController.php';
require_once dirname(__DIR__) . '/app/controllers/AuthController.php';
require_once dirname(__DIR__) . '/app/controllers/CoursController.php';
require_once dirname(__DIR__) . '/app/controllers/ContactController.php';
require_once dirname(__DIR__) . '/app/controllers/AdminController.php';

// Services
require_once dirname(__DIR__) . '/app/services/Router.php';