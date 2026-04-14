<?php

class CourseManager extends AbstractManager
{
    public function findAll(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM courses ORDER BY day, start_time'
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $courses = [];
        foreach ($rows as $row) {
            $course = new Course(
                $row['name'],
                $row['type'],
                $row['level'],
                $row['coach'],
                $row['day'],
                $row['start_time'],
                $row['end_time'],
                $row['max_capacity'],
                $row['description']?? null,
                $row['id_class']
            );
            $courses[] = $course;
        }
        return $courses;
    }

    public function findOne(int $id): ?Course
    {
        $query = $this->db->prepare(
            'SELECT * FROM courses WHERE id_class = :id'
        );
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Course(
            $row['name'],
            $row['type'],
            $row['level'],
            $row['coach'],
            $row['day'],
            $row['start_time'],
            $row['end_time'],
            $row['max_capacity'],
            $row['description']?? null,
            $row['id_class']
        );
    }

    public function findByDay(string $day): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM courses WHERE day = :day ORDER BY start_time'
        );
        $query->execute(['day' => $day]);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $courses = [];
        foreach ($rows as $row) {
            $course = new Course(
                $row['name'],
                $row['type'],
                $row['level'],
                $row['coach'],
                $row['day'],
                $row['start_time'],
                $row['end_time'],
                $row['max_capacity'],
                $row['description'],
                $row['id_class']
            );
            $courses[] = $course;
        }
        return $courses;
    }

    public function create(Course $course): bool
    {
        $query = $this->db->prepare('
            INSERT INTO courses 
            (name, type, level, coach, day, start_time, end_time, max_capacity, description)
            VALUES 
            (:name, :type, :level, :coach, :day, :start_time, :end_time, :max_capacity, :description)
        ');
        return $query->execute([
            'name'         => $course->getName(),
            'type'         => $course->getType(),
            'level'        => $course->getLevel(),
            'coach'        => $course->getCoach(),
            'day'          => $course->getDay(),
            'start_time'   => $course->getStartTime(),
            'end_time'     => $course->getEndTime(),
            'max_capacity' => $course->getMaxCapacity(),
            'description'  => $course->getDescription(),
        ]);
    }

    public function update(Course $course): bool
    {
        $query = $this->db->prepare('
            UPDATE courses SET
                name         = :name,
                type         = :type,
                level        = :level,
                coach        = :coach,
                day          = :day,
                start_time   = :start_time,
                end_time     = :end_time,
                max_capacity = :max_capacity,
                description  = :description
            WHERE id_class = :id
        ');
        return $query->execute([
            'name'         => $course->getName(),
            'type'         => $course->getType(),
            'level'        => $course->getLevel(),
            'coach'        => $course->getCoach(),
            'day'          => $course->getDay(),
            'start_time'   => $course->getStartTime(),
            'end_time'     => $course->getEndTime(),
            'max_capacity' => $course->getMaxCapacity(),
            'description'  => $course->getDescription(),
            'id'           => $course->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $query = $this->db->prepare(
            'DELETE FROM courses WHERE id_class = :id'
        );
        return $query->execute(['id' => $id]);
    }
}