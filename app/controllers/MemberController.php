<?php


class MemberController extends AbstractController
{
    public function index(): void
    {
        // Sécurité : redirige si pas connecté ou pas member
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
            $this->redirect('login');
        }

        $memberManager = new MemberManager();
        $member = $memberManager->findOne($_SESSION['user']['id']);

        $subscription = null;
        if ($member->getIdSubscription()) {
            $subscriptionManager = new SubscriptionManager();
            $subscription = $subscriptionManager->findOne($member->getIdSubscription());
        }

        $this->render('member', [
            'member' => $member,
            'subscription' => $subscription
        ]);
    }
}