<?php

class Course {

    public function __construct(
        private string  $name,
        private string  $type,
        private string  $level,
        private string  $coach,
        private string  $day,
        private string  $startTime,
        private string  $endTime,
        private int     $maxCapacity = 20,
      
        private ?int    $id          = null
    ) {}

    // ── Getters ──
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getLevel(): string {
        return $this->level;
    }

    public function getCoach(): string {
        return $this->coach;
    }

    public function getDay(): string {
        return $this->day;
    }

    public function getStartTime(): string {
        return $this->startTime;
    }

    public function getEndTime(): string {
        return $this->endTime;
    }

    public function getMaxCapacity(): int {
        return $this->maxCapacity;
    }

  

    // ── Setters ──
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function setLevel(string $level): void {
        $this->level = $level;
    }

    public function setCoach(string $coach): void {
        $this->coach = $coach;
    }

    public function setDay(string $day): void {
        $this->day = $day;
    }

    public function setStartTime(string $startTime): void {
        $this->startTime = $startTime;
    }

    public function setEndTime(string $endTime): void {
        $this->endTime = $endTime;
    }

    public function setMaxCapacity(int $maxCapacity): void {
        $this->maxCapacity = $maxCapacity;
    }

 
}