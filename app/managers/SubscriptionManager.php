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
}