<?php

class AdminManager extends AbstractManager
{
    protected string $table = 'admins';
    
    public function findAll(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM admins ORDER BY name'
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $admins = [];
        foreach ($rows as $row) {
            $admin = new Admin(
                $row['name'],
                $row['email'],
                $row['password'],
                $row['role'],
                $row['created_at'],
                $row['id_admin']
            );
            $admins[] = $admin;
        }
        return $admins;
    }

    public function findOne(int $id): ?Admin
    {
        $query = $this->db->prepare(
            'SELECT * FROM admins WHERE id_admin = :id'
        );
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Admin(
            $row['name'],
            $row['email'],
            $row['password'],
            $row['role'],
            $row['created_at'],
            $row['id_admin']
        );
    }

    public function findByEmail(string $email): ?Admin
    {
        $query = $this->db->prepare(
            'SELECT * FROM admins WHERE email = :email'
        );
        $query->execute(['email' => $email]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Admin(
            $row['name'],
            $row['email'],
            $row['password'],
            $row['role'],
            $row['created_at'],
            $row['id_admin']
        );
    }
}