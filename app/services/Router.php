<?php

class Router {

    public function handleRequest(array $get): void
    {
        if (!empty($get['route'])) {

            // ── Pages publiques ──
            if ($get['route'] === 'home') {
                $controller = new HomeController();
                $controller->index();
            }
            else if ($get['route'] === 'classes') {
                $controller = new CoursController();
                $controller->index();
            }
            else if ($get['route'] === 'membership') {
                $controller = new MembershipController();
                $controller->index();
            }
            else if ($get['route'] === 'contact') {
                $controller = new ContactController();
                $controller->index();
            }
            else if ($get['route'] === 'check-contact') {
                $controller = new ContactController();
                $controller->send();
            }
            else if ($get['route'] === 'contact') {
            $controller = new ContactController();
            $controller->index();
            }
            else if ($get['route'] === 'check-contact') {
                $controller = new ContactController();
                $controller->send();
            }
            else if ($get['route'] === 'membership') {
                $controller = new MembershipController();
                $controller->index();
            }
           
            // ── Authentification ──
            else if ($get['route'] === 'login') {
                $controller = new AuthController();
                $controller->loginForm();
            }
            else if ($get['route'] === 'check-login') {
                $controller = new AuthController();
                $controller->login();
            }
            else if ($get['route'] === 'register') {
                $controller = new AuthController();
                $controller->registerForm();
            }
            else if ($get['route'] === 'check-register') {
                $controller = new AuthController();
                $controller->register();
            }
            else if ($get['route'] === 'logout') {
                $controller = new AuthController();
                $controller->logout();
            }

            // ── Admin Dashboard ──
            else if ($get['route'] === 'admin') {
                $controller = new AdminController();
                $controller->index();
            }

            // ── Admin CRUD Members ──
            else if ($get['route'] === 'admin-members') {
                $controller = new AdminController();
                $controller->listMembers();
            }
            else if ($get['route'] === 'admin-create-member') {
                $controller = new AdminController();
                $controller->createMember();
            }
            else if ($get['route'] === 'admin-check-create-member') {
                $controller = new AdminController();
                $controller->checkCreateMember();
            }
            else if ($get['route'] === 'admin-update-member') {
                $controller = new AdminController();
                $controller->updateMember();
            }
            else if ($get['route'] === 'admin-check-update-member') {
                $controller = new AdminController();
                $controller->checkUpdateMember();
            }
            else if ($get['route'] === 'admin-delete-member') {
                $controller = new AdminController();
                $controller->deleteMember();
            }

            // ── Admin CRUD Courses ──
            else if ($get['route'] === 'admin-courses') {
                $controller = new AdminController();
                $controller->listCourses();
            }
            else if ($get['route'] === 'admin-create-course') {
                $controller = new AdminController();
                $controller->createCourse();
            }
            else if ($get['route'] === 'admin-check-create-course') {
                $controller = new AdminController();
                $controller->checkCreateCourse();
            }
            else if ($get['route'] === 'admin-update-course') {
                $controller = new AdminController();
                $controller->updateCourse();
            }
            else if ($get['route'] === 'admin-check-update-course') {
                $controller = new AdminController();
                $controller->checkUpdateCourse();
            }
            else if ($get['route'] === 'admin-delete-course') {
                $controller = new AdminController();
                $controller->deleteCourse();
            }

            // ── Admin Subscriptions ──
            else if ($get['route'] === 'admin-subscriptions') {
                $controller = new AdminController();
                $controller->listSubscriptions();
            }

            // ── 404 ──
            else {
                http_response_code(404);
                $controller = new HomeController();
                $controller->notFound();
            }

        }
        else {
            // Pas de route → Home
            $controller = new HomeController();
            $controller->index();
        }
    }
}