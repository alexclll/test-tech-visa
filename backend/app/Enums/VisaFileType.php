<?php

namespace App\Enums;

enum VisaFileType: string
{
    case Passport = 'passport';
    case Photo = 'photo';
    case Form = 'form';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
