<?php
namespace App;

class Storage
{
    private string $filePath;

    public function __construct(string $_filePath)
    {
        $this->filePath = $_filePath;
    }

    public function load(): array
    {
        if (!file_exists($this->filePath)) {
            return ['maxId' => 0, 'tasks' => []];
        }

        $jsonString = file_get_contents($this->filePath);

        if ($jsonString === false) {
            throw new \Exception(
                "Не удалось прочитать файл: {$this->filePath}"
            );
        }

        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                "Некорректный JSON: " . json_last_error_msg()
            );
        }

        if (!is_array($data)) {
            throw new \Exception(
                "Данные хранилища должны быть массивом."
            );
        }

        if (!isset($data['maxId']) || !isset($data['tasks'])) {
            throw new \Exception(
                "Неверный формат хранилища: отсутствуют \"maxId\" или \"tasks\"."
            );
        }

        return $data;
    }

    public function save(array $_tasks, int $_maxId)
    {
        $arrayData = array_map(function($task) {
            return $task->toArray();
        }, $_tasks);

        $dataToSave = [
            'maxId' => $_maxId,
            'tasks' => $arrayData
        ];

        $jsonData = json_encode($dataToSave, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($jsonData === false) {
            throw new \Exception(
                "Не удалось закодировать данные в JSON: " . json_last_error_msg()
            );
        }

        $result = file_put_contents($this->filePath, $jsonData);

        if ($result === false) {
            throw new \Exception(
                "Не удалось записать файл: {$this->filePath}"
            );
        }
    }
}