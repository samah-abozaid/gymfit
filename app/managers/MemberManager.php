<?php

class MemberManager extends AbstractManager
{
    protected string $table = 'members'; 
    
    public function findAll(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM members ORDER BY last_name'
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $members = [];
        foreach ($rows as $row) {
            $member = new Member(
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['password'],
                $row['phone'],
                $row['status'],
                $row['id_subscription'],
                $row['id_member'],
                $row['registration_date']
            );
            $members[] = $member;
        }
        return $members;
    }

    public function findOne(int $id): ?Member
    {
        $query = $this->db->prepare(
            'SELECT * FROM members WHERE id_member = :id'
        );
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Member(
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['password'],
            $row['phone'],
            $row['status'],
            $row['id_subscription'],
            $row['id_member'],
            $row['registration_date']
        );
    }

    public function findByEmail(string $email): ?Member
    {
        $query = $this->db->prepare(
            'SELECT * FROM members WHERE email = :email'
        );
        $query->execute(['email' => $email]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Member(
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['password'],
            $row['phone'],
            $row['status'],
            $row['id_subscription'],
            $row['id_member'],
            $row['registration_date']
        );
    }

    public function create(Member $member): bool
    {
        $query = $this->db->prepare('
            INSERT INTO members 
            (first_name, last_name, email, password, phone, status, id_subscription)
            VALUES 
            (:first_name, :last_name, :email, :password, :phone, :status, :id_subscription)
        ');
        return $query->execute([
            'first_name'      => $member->getFirstName(),
            'last_name'       => $member->getLastName(),
            'email'           => $member->getEmail(),
            'password'        => $member->getPassword(),
            'phone'           => $member->getPhone(),
            'status'          => $member->getStatus(),
            'id_subscription' => $member->getIdSubscription(),
        ]);
    }

    public function update(Member $member): bool
    {
        $query = $this->db->prepare('
            UPDATE members SET
                first_name      = :first_name,
                last_name       = :last_name,
                email           = :email,
                phone           = :phone,
                status          = :status,
                id_subscription = :id_subscription
            WHERE id_member = :id
        ');
        return $query->execute([
            'first_name'      => $member->getFirstName(),
            'last_name'       => $member->getLastName(),
            'email'           => $member->getEmail(),
            'phone'           => $member->getPhone(),
            'status'          => $member->getStatus(),
            'id_subscription' => $member->getIdSubscription(),
            'id'              => $member->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $query = $this->db->prepare(
            'DELETE FROM members WHERE id_member = :id'
        );
        return $query->execute(['id' => $id]);
    }

    public function countActive(): int
    {
        $query = $this->db->prepare(
            "SELECT COUNT(*) FROM members WHERE status = 'active'"
        );
        $query->execute();
        return (int) $query->fetchColumn();
    }
}