<?php
namespace App;

class Task {
    private int $id;
    private string $title;
    private string $priority;
    private bool $isCompleted;

    public function __construct(int $_id, string $_title, string $_priority, bool $_isCompleted = false) {
        $this->id = $_id;
        $this->title = $_title;
        $this->priority = strtolower($_priority);
        $this->isCompleted = $_isCompleted;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getPriority(): string {
        return $this->priority;
    }

    public function getIsCompleted(): bool {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $_isCompleted): void {
        $this->isCompleted = $_isCompleted;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'priority' => $this->priority,
            'isCompleted' => $this->isCompleted
        ];
    }
}