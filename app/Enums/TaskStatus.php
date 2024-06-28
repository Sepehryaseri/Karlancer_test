<?php

namespace App\Enums;

enum TaskStatus: int
{
    case IN_COMPLETE = 0;
    case COMPLETE = 1;

    public function label(): string
    {
        return TaskStatus::getLabel($this);
    }

    public static function getLabel(self $key): string
    {
        return match ($key) {
            self::COMPLETE => 'Done',
            self::IN_COMPLETE => 'To Do',
        };
    }
}
