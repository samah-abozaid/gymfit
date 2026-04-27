<?php

class CoursController extends AbstractController
{
    public function index(): void
    {
        $courseManager = new CourseManager();

        $this->render('classes', [
            'title'   => 'GymFit — Classes',
            'courses' => $courseManager->findAll(),
        ]);
    }
}