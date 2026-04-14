<?php

class Admin {

    public function __construct(
        private string  $name,
        private string  $email,
        private string  $password,
        private string  $role      = 'admin',
        private ?string $createdAt = null,
        private ?int    $id        = null
    ) {
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    // ── Getters ──
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    // ── Setters ──
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }
}