<?php
namespace App;

use Symfony\Component\Console\Output\OutputInterface;

class Application {
    private TaskManager $taskManager;
    private OutputInterface $output;

    public function __construct(TaskManager $_taskManager, $_output) {
        $this->taskManager = $_taskManager;
        $this->output = $_output;
    }

    public function run() : void {
        $this->output->writeln("<info>=== Консольный список задач (Todo App) ===");

        while (true) {
            $this->showMenu();
            $choice = trim(readline("Выберите пункт меню: "));

            try {
                switch ($choice) {
                    case '1':
                        $this->listTasks();
                        break;
                    case '2':
                        $this->addTask();
                        break;
                    case '3':
                        $this->deleteTask();
                        break;
                    case '0':
                        $this->output->writeln("<comment>Выход из программы.");
                        return;
                    default:
                        $this->output->writeln("<error>Неверный выбор. Пожалуйста, введите число от 0 до 3.");
                }
            }
            catch (\Exception $e) {
                $this->output->writeln("<error>Ошибка: " . $e->getMessage());
            }

            $this->output->writeln("");
            $this->output->writeln("<info>--------------------------------------------------");
        }
    }

    private function showMenu(): void {
        echo "1. Посмотреть список задач\n";
        echo "2. Добавить новую задачу\n";
        echo "3. Удалить задачу по ID\n";
        echo "0. Выход\n";
        echo "\n";
    }

    private function listTasks(): void {
        $priorityInput = trim(readline("Фильтр по приоритету (low, medium, high) или нажмите Enter для всех: "));
        $priority = $priorityInput === '' ? null : $priorityInput;

        $tasks = $this->taskManager->getAllTasks($priority);

        if (empty($tasks)) {
            $this->output->writeln("<comment>Список задач пуст.");
            return;
        }

        echo "Список задач:\n";
        foreach ($tasks as $task) {
            $this->output->writeln("<question>  [ID: {$task->getId()}] [Приоритет: {$task->getPriority()}] {$task->getTitle()}");
        }
    }

    private function addTask(): void {
        $title = trim(readline("Введите название задачи: "));

        if ($title === '') {
            $this->output->writeln("<error>Название задачи не может быть пустым.");
            return;
        }

        $priority = trim(readline("Введите приоритет (low, medium, high): "));

        $this->taskManager->addTask($title, $priority);

        $this->output->writeln("<comment>Задача успешно добавлена!");
    }

    private function deleteTask(): void {
        $idInput = trim(readline("Введите ID задачи для удаления: "));

        if (!is_numeric($idInput)) {
            $this->output->writeln("<error>ID должен быть целым числом.");
            return;
        }

        $this->taskManager->deleteTask((int)$idInput);

        $this->output->writeln("<comment>Задача успешно удалена!");
    }
}