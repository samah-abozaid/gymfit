<?php

class SubscriptionManager extends AbstractManager
{
    protected string $table = 'subscriptions';

    // Récupère tous les abonnements
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM subscriptions ORDER BY monthly_price"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupère un abonnement par ID
    public function findOne(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM subscriptions 
             WHERE id_subscription = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}