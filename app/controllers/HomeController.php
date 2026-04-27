<?php

class HomeController extends AbstractController
{
    public function index(): void
    {
        $courseManager       = new CourseManager();
        $subscriptionManager = new SubscriptionManager();

        $this->render('home', [
            'title'         => 'GymFit — Premium Fitness Cairo',
            'courses'       => $courseManager->findAll(),
            'subscriptions' => $subscriptionManager->findAll()
        ]);
    }
}