<?php

namespace App\Models;

enum RoleEnum
{
    case COMMERCIAL;
    case CONSULTANT;
    case TECHNICIEN;
    case ADMINISTRATEUR;

    public static function tryFrom($value)
    {
        $matched = array_search($value, array_column(RoleEnum::cases(), "name"));
        if ($matched === false) {
            return null;
        }
        return $value;
    }
}
