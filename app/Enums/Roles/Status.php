<?php

namespace App\Enums\Roles;

enum Status:string
{
    case USER = 'user';
    case MODERATOR = 'moderator'; 
    case ADMIN = 'admin';
}

