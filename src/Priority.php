<?php
namespace App;

// изначально хотел сделать enum, но версия PHP storm 2021 года, которую я установил, не поддерживала PHP 8.1, поэтому пришлось изворачиваться
/*
enum Priority: string {
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
*/

// класс с константами вместо enum
class Priority {
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const HIGH = 'high';

    public static function isValid(string $priority): bool {
        return in_array($priority, [self::LOW, self::MEDIUM, self::HIGH], true);
    }
}