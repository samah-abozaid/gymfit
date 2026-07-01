<?php

class SubscriptionManager extends AbstractManager
{
    protected string $table = 'subscriptions';
    
    public function findAll(): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM subscriptions ORDER BY monthly_price'
        );
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $subscriptions = [];
        foreach ($rows as $row) {
            $subscription = new Subscription(
                $row['name'],
                $row['monthly_price'],
                $row['class_access'],
                $row['coaching_access'],
                $row['sauna_access'],
                $row['description'],
                $row['id_subscription']
            );
            $subscriptions[] = $subscription;
        }
        return $subscriptions;
    }

    public function findOne(int $id): ?Subscription
    {
        $query = $this->db->prepare(
            'SELECT * FROM subscriptions WHERE id_subscription = :id'
        );
        $query->execute(['id' => $id]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Subscription(
            $row['name'],
            $row['monthly_price'],
            $row['class_access'],
            $row['coaching_access'],
            $row['sauna_access'],
            $row['description'],
            $row['id_subscription']
        );
    }

    public function create(Subscription $sub): bool
    {
        $query = $this->db->prepare('
            INSERT INTO subscriptions (name, monthly_price, class_access, coaching_access, sauna_access, description)
            VALUES (:name, :price, :class, :coaching, :sauna, :desc)
        ');
        return $query->execute([
            'name'     => $sub->getName(),
            'price'    => $sub->getMonthlyPrice(),
            'class'    => $sub->hasClassAccess()    ? 1 : 0,
            'coaching' => $sub->hasCoachingAccess() ? 1 : 0,
            'sauna'    => $sub->hasSaunaAccess()    ? 1 : 0,
            'desc'     => $sub->getDescription(),
        ]);
    }

    public function update(Subscription $sub): bool
    {
        $query = $this->db->prepare('
            UPDATE subscriptions SET
                name            = :name,
                monthly_price   = :price,
                class_access    = :class,
                coaching_access = :coaching,
                sauna_access    = :sauna,
                description     = :desc
            WHERE id_subscription = :id
        ');
        return $query->execute([
            'name'     => $sub->getName(),
            'price'    => $sub->getMonthlyPrice(),
            'class'    => $sub->hasClassAccess()    ? 1 : 0,
            'coaching' => $sub->hasCoachingAccess() ? 1 : 0,
            'sauna'    => $sub->hasSaunaAccess()    ? 1 : 0,
            'desc'     => $sub->getDescription(),
            'id'       => $sub->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $query = $this->db->prepare(
            'DELETE FROM subscriptions WHERE id_subscription = :id'
        );
        return $query->execute(['id' => $id]);
    }

    public function countAll(): int
    {
        $query = $this->db->prepare('SELECT COUNT(*) FROM subscriptions');
        $query->execute();
        return (int) $query->fetchColumn();
    }
}