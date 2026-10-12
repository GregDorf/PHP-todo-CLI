<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Storage;
use App\TaskManager;
use App\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

$filePath = __DIR__ . '/tasks.json';
$output = new ConsoleOutput();

try {
    $storage = new Storage($filePath);
    $taskManager = new TaskManager($storage);
    $app = new Application($taskManager, $output);

    $app->run();
} catch (\Exception $e) {
    $output->writeln("<error>Критическая ошибка: {$e->getMessage()}");
}
