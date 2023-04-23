<?php
declare(strict_types=1);

namespace App\Helper;

enum RolesHelper: string {
    case RoleAdmin = 'ROLE_ADMIN';
    case RoleStudent = 'ROLE_STUDENT';
    case RoleTeacher = 'ROLE_TEACHER';
}
