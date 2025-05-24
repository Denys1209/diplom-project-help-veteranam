<?php

namespace App\Enums;


enum PhotoType: int
{
    case REGULAR = 0;
    case COMPLETION = 1;

    public function label(): string
    {
        return match($this) {
            PhotoType::REGULAR => 'Звичайне фото',
            PhotoType::COMPLETION => 'Фото виконаної роботи',
        };
    }
}
