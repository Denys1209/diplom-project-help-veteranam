<?php

namespace App\Enums;

enum HelpRequestUrgency: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match($this) {
            HelpRequestUrgency::LOW => 'Низька',
            HelpRequestUrgency::MEDIUM => 'Середня',
            HelpRequestUrgency::HIGH => 'Висока',
            HelpRequestUrgency::CRITICAL => 'Критична',
        };
    }
}
