<?php

class MemberManager extends AbstractManager
{
    protected string $table = 'members';

    public function findAll(): array
    {
        $query = $this->db->prepare(
            "SELECT * FROM members WHERE id_role = 2 ORDER BY last_name"
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $members = [];
        foreach ($rows as $row) {
            $members[] = new Member(
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['password'],
                $row['phone'],
                $row['status'],
                $row['id_subscription'],
                $row['id_member'],
                $row['registration_date'],
                $row['avatar'] ?? null,
                $row['id_role']
            );
        }
        return $members;
    }

    public function findAllWithSubscription(): array
    {
        $query = $this->db->prepare(
            "SELECT m.*, s.name AS subscription_name
             FROM members m
             LEFT JOIN subscriptions s ON m.id_subscription = s.id_subscription
             WHERE m.id_role = 2
             ORDER BY m.last_name"
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                'member' => new Member(
                    $row['first_name'],
                    $row['last_name'],
                    $row['email'],
                    $row['password'],
                    $row['phone'],
                    $row['status'],
                    $row['id_subscription'],
                    $row['id_member'],
                    $row['registration_date'],
                    $row['avatar'] ?? null,
                    $row['id_role']
                ),
                'subscriptionName' => $row['subscription_name'],
            ];
        }
        return $results;
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
            $row['registration_date'],
            $row['avatar'] ?? null,
            $row['id_role']
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
            $row['registration_date'],
            $row['avatar'] ?? null,
            $row['id_role']
        );
    }

    // ── Utilisée par le login : récupère le membre + le nom de son rôle via la table roles ──
    public function findByEmailWithRole(string $email): ?array
    {
        $query = $this->db->prepare('
            SELECT m.*, r.name AS role_name
            FROM members m
            JOIN roles r ON m.id_role = r.id_role
            WHERE m.email = :email
        ');
        $query->execute(['email' => $email]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return [
            'member' => new Member(
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['password'],
                $row['phone'],
                $row['status'],
                $row['id_subscription'],
                $row['id_member'],
                $row['registration_date'],
                $row['avatar'] ?? null,
                $row['id_role']
            ),
            'role' => $row['role_name'],
        ];
    }

    public function create(Member $member): bool
    {
        $query = $this->db->prepare('
            INSERT INTO members
            (first_name, last_name, email, password, phone, status, id_subscription, avatar, id_role)
            VALUES
            (:first_name, :last_name, :email, :password, :phone, :status, :id_subscription, :avatar, :id_role)
        ');
        return $query->execute([
            'first_name'      => $member->getFirstName(),
            'last_name'       => $member->getLastName(),
            'email'           => $member->getEmail(),
            'password'        => $member->getPassword(),
            'phone'           => $member->getPhone(),
            'status'          => $member->getStatus(),
            'id_subscription' => $member->getIdSubscription(),
            'avatar'          => $member->getAvatar(),
            'id_role'         => $member->getIdRole(),
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

    public function updateProfile(Member $member): bool
    {
        $query = $this->db->prepare('
            UPDATE members SET
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                phone      = :phone
            WHERE id_member = :id
        ');
        return $query->execute([
            'first_name' => $member->getFirstName(),
            'last_name'  => $member->getLastName(),
            'email'      => $member->getEmail(),
            'phone'      => $member->getPhone(),
            'id'         => $member->getId(),
        ]);
    }

    public function updatePassword(Member $member): bool
    {
        $query = $this->db->prepare('
            UPDATE members SET password = :password WHERE id_member = :id
        ');
        return $query->execute([
            'password' => $member->getPassword(),
            'id'       => $member->getId(),
        ]);
    }

    public function updateAvatar(int $id, ?string $avatar): bool
    {
        $query = $this->db->prepare(
            'UPDATE members SET avatar = :avatar WHERE id_member = :id'
        );
        return $query->execute(['avatar' => $avatar, 'id' => $id]);
    }

    public function countActive(): int
    {
        $query = $this->db->prepare(
            "SELECT COUNT(*) FROM members WHERE status = 'active' AND id_role = 2"
        );
        $query->execute();
        return (int) $query->fetchColumn();
    }

    public function countAll(): int
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM members WHERE id_role = 2");
        $query->execute();
        return (int) $query->fetchColumn();
    }
}
