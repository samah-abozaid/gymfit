<?php

class CourseManager extends AbstractManager
{
    protected string $table = 'courses';

    // Récupère tous les cours
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM courses ORDER BY day, start_time"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupère les cours par jour
    public function findByDay(string $day): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM courses 
             WHERE day = :day 
             ORDER BY start_time"
        );
        $stmt->execute(['day' => $day]);
        return $stmt->fetchAll();
    }

    // Récupère un cours par ID
    public function findOne(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM courses WHERE id_class = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}