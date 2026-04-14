<?php

class Member {

    public function __construct(
        private string  $firstName,
        private string  $lastName,
        private string  $email,
        private string  $password,
        private string  $phone,
        private string  $status           = 'pending',
        private ?int    $idSubscription   = null,
        private ?int    $id               = null,
        private ?string $registrationDate = null
    ) {
        $this->registrationDate = $registrationDate ?? date('Y-m-d H:i:s');
    }

    // ── Getters ──
    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getIdSubscription(): ?int {
        return $this->idSubscription;
    }

    public function getRegistrationDate(): ?string {
        return $this->registrationDate;
    }

    // ── Setters ──
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function setIdSubscription(?int $idSubscription): void {
        $this->idSubscription = $idSubscription;
    }
}