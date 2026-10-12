<?php
namespace App;

class TaskManager {
    private array $tasks;
    private int $maxId;
    private Storage $storage;

    public function __construct(Storage $_storage) {
        $this->storage = $_storage;
        $storageData = $this->storage->load();

        $this->maxId = $storageData['maxId'] ?? 0;
        $this->tasks = [];

        foreach ($storageData['tasks'] as $taskData) {
            $isCompleted = isset($taskData['isCompleted']) && (bool)$taskData['isCompleted'];
            $this->tasks[] = new Task(
                $taskData['id'],
                $taskData['title'],
                $taskData['priority'],
                $isCompleted
            );
        }
    }

    public function getAllTasks(?string $_priority = null): array {
        if ($_priority === null) {
            return array_values(array_filter($this->tasks, function(Task $st) {
                return $st->getIsCompleted() === false;
            }));
        }

        if (Priority::isValid($_priority)) {
            return array_values(array_filter($this->tasks, function(Task $st) use ($_priority) {
                return $st->getPriority() === $_priority && $st->getIsCompleted() === false;
            }));
        }

        throw new \Exception("Приоритет не существует: {$_priority}");
    }

    public function addTask(string $_title, string $_priority): void {
        if (!Priority::isValid($_priority)) {
            throw new \Exception("Приоритет не существует: {$_priority}");
        }

        $id = ++$this->maxId;

        $task = new Task($id, $_title, $_priority, false);
        $this->tasks[] = $task;

        $this->storage->save($this->tasks, $this->maxId);
    }

    public function deleteTask(int $_id, bool $deleteFromJson = false) {
        $targetTask = null;
        $indexToRemove = null;

        foreach ($this->tasks as $index => $task) {
            if ($task->getId() === $_id) {
                $targetTask = $task;
                $indexToRemove = $index;
                break;
            }
        }

        if ($targetTask === null) {
            throw new \Exception("Заданный ID не существует в списке: {$_id}");
        }

        if ($deleteFromJson) {
            unset($this->tasks[$indexToRemove]);
            $this->tasks = array_values($this->tasks);
        } else {
            $targetTask->setIsCompleted(true);
        }

        $this->storage->save($this->tasks, $this->maxId);
    }
}
