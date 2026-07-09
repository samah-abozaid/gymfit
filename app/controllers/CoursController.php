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

    // API JSON pour le filtre des cours en fetch (sans rechargement)
    public function apiCourses(): void
    {
        $courseManager = new CourseManager();

        $type = $_GET['type'] ?? 'All';
        $courses = ($type === 'All')
            ? $courseManager->findAll()
            : $courseManager->findByType($type);

        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'name'      => $course->getName(),
                'type'      => $course->getType(),
                'level'     => $course->getLevel(),
                'coach'     => $course->getCoach(),
                'day'       => $course->getDay(),
                'startTime' => $course->getStartTime(),
                'endTime'   => $course->getEndTime(),
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}