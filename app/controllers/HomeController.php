<?php

class HomeController extends AbstractController
{
    public function index(): void
    {
        // Récupère les cours depuis la BDD
        $courseManager = new CourseManager();
        $courses = $courseManager->findAll();

        // Récupère les abonnements
        $subscriptionManager = new SubscriptionManager();
        $subscriptions = $subscriptionManager->findAll();

        // Envoie les données à la vue
        $this->render('home', [
            'courses'       => $courses,
            'subscriptions' => $subscriptions
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('404');
    }
}