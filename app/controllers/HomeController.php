<?php

class HomeController extends AbstractController
{
    public function index(): void
    {
        $days  = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $today = date('l');

        $courseManager       = new CourseManager();
        $subscriptionManager = new SubscriptionManager();

        $coursesByDay = [];

        foreach ($days as $day) {
            $dayCourses = $courseManager->findByDay($day); // ← 1 requête par jour
            if (!empty($dayCourses)) {
                $coursesByDay[$day] = $dayCourses;
            }
        }

        $this->render('home', [
            'title'         => 'GymFit — Premium Fitness Cairo',
            'coursesByDay'  => $coursesByDay,
            'today'         => $today,
            'subscriptions' => $subscriptionManager->findAll()
        ]);
    }

    public function notFound(): void
    {
        require_once __DIR__ . '/../views/notFound.phtml';
    }
}