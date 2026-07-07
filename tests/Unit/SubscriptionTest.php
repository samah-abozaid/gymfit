<?php

use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testConstructorAssignsRequiredFields(): void
    {
        $subscription = new Subscription(name: 'Essentiel', monthlyPrice: 29.90);

        $this->assertSame('Essentiel', $subscription->getName());
        $this->assertSame(29.90, $subscription->getMonthlyPrice());
    }

    public function testDefaultAccessesAreDisabled(): void
    {
        $subscription = new Subscription(name: 'Essentiel', monthlyPrice: 29.90);

        $this->assertFalse($subscription->hasClassAccess());
        $this->assertFalse($subscription->hasCoachingAccess());
        $this->assertFalse($subscription->hasSaunaAccess());
        $this->assertNull($subscription->getDescription());
        $this->assertNull($subscription->getId());
    }

    public function testConstructorWithAllOptions(): void
    {
        $subscription = new Subscription(
            name:           'Premium',
            monthlyPrice:   59.90,
            classAccess:    true,
            coachingAccess: true,
            saunaAccess:    true,
            description:    'Acces illimite a tout',
            id:             5
        );

        $this->assertTrue($subscription->hasClassAccess());
        $this->assertTrue($subscription->hasCoachingAccess());
        $this->assertTrue($subscription->hasSaunaAccess());
        $this->assertSame('Acces illimite a tout', $subscription->getDescription());
        $this->assertSame(5, $subscription->getId());
    }

    public function testSettersUpdateValues(): void
    {
        $subscription = new Subscription(name: 'Essentiel', monthlyPrice: 29.90);

        $subscription->setId(2);
        $subscription->setName('Confort');
        $subscription->setMonthlyPrice(39.90);
        $subscription->setDescription('Avec cours collectifs');
        $subscription->setClassAccess(true);
        $subscription->setCoachingAccess(true);
        $subscription->setSaunaAccess(true);

        $this->assertSame(2, $subscription->getId());
        $this->assertSame('Confort', $subscription->getName());
        $this->assertSame(39.90, $subscription->getMonthlyPrice());
        $this->assertSame('Avec cours collectifs', $subscription->getDescription());
        $this->assertTrue($subscription->hasClassAccess());
        $this->assertTrue($subscription->hasCoachingAccess());
        $this->assertTrue($subscription->hasSaunaAccess());
    }

    public function testDescriptionCanBeResetToNull(): void
    {
        $subscription = new Subscription(name: 'Essentiel', monthlyPrice: 29.90, description: 'Temporaire');
        $subscription->setDescription(null);

        $this->assertNull($subscription->getDescription());
    }
}
