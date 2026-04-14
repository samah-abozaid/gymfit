<?php

class Subscription {

    public function __construct(
        private string $name,
        private float  $monthlyPrice,
        private bool   $classAccess    = false,
        private bool   $coachingAccess = false,
        private bool   $saunaAccess    = false,
        private ?string $description   = null,
        private ?int   $id             = null
    ) {}

    // ── Getters ──
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getMonthlyPrice(): float {
        return $this->monthlyPrice;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function hasClassAccess(): bool {
        return $this->classAccess;
    }

    public function hasCoachingAccess(): bool {
        return $this->coachingAccess;
    }

    public function hasSaunaAccess(): bool {
        return $this->saunaAccess;
    }

    // ── Setters ──
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setMonthlyPrice(float $monthlyPrice): void {
        $this->monthlyPrice = $monthlyPrice;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setClassAccess(bool $classAccess): void {
        $this->classAccess = $classAccess;
    }

    public function setCoachingAccess(bool $coachingAccess): void {
        $this->coachingAccess = $coachingAccess;
    }

    public function setSaunaAccess(bool $saunaAccess): void {
        $this->saunaAccess = $saunaAccess;
    }
}