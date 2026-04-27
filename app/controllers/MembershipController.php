<?php

class MembershipController extends AbstractController
{
    public function index(): void
    {
        $subscriptionManager = new SubscriptionManager();

        $this->render('membership', [
            'title'         => 'GymFit — Membership',
            'subscriptions' => $subscriptionManager->findAll()
        ]);
    }
}