<?php
namespace App\Enums;

enum ApprovalStatus: string
{
    case WAITING = 'waiting';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            ApprovalStatus::WAITING => 'Очікує підтвердження',
            ApprovalStatus::APPROVED => 'Підтверджено',
            ApprovalStatus::REJECTED => 'Відхилено',
        };
    }
}
