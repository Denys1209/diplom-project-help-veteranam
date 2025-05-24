<?php

namespace App\Enums;

enum HelpRequestStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            HelpRequestStatus::PENDING => 'В очікуванні',
            HelpRequestStatus::IN_PROGRESS => 'В процесі',
            HelpRequestStatus::COMPLETED => 'Виконано',
            HelpRequestStatus::CANCELLED => 'Скасовано',
        };
    }
}
