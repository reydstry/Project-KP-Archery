<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case COACH = 'coach';
    case MEMBER = 'member';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::COACH => 'Pelatih',
            self::MEMBER => 'Member/Orang Tua',
        };
    }
}