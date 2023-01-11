<?php

namespace App\Enums\Comment;

enum Status:int
{
    case NEW = 0;
    case ACCEPT = 1; 
    case DECLINE = 2;
    case CHANGED = 3;
}