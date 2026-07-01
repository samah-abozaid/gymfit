<?php

class CoursController extends AbstractController
{
    public function index(): void
    {
        $days  = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $today = date('l');

        $courseManager       = new CourseManager();
        $coursesByDay = [];

        foreach ($days as $day) {
            $dayCourses = $courseManager->findByDay($day); // ← 1 requête par jour
            if (!empty($dayCourses)) {
                $coursesByDay[$day] = $dayCourses;
            }
        }


        $this->render('classes', [
            'today' => $today,
            'title'   => 'GymFit — Classes',
            'courses' => $courseManager->findAll(),
            'coursesByDay'  => $coursesByDay,
        ]);
    }
}