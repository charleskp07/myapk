<?php

namespace App\Enums;

enum RoleEnums:string
{
    case ADMIN = 'admin';
    case TEACHER = 'enseignant';
    case STUDENT = 'apprenant';
}
