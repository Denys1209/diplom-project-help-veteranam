<?php

namespace App\Enums;

enum UserRole: string
{
    case VOLUNTEER = 'volunteer';
    case VETERAN = 'veteran';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            UserRole::VOLUNTEER => 'Волонтер',
            UserRole::VETERAN => 'Ветеран',
            UserRole::ADMIN => 'Адміністратор',
        };
    }
}
