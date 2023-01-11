<?php

namespace App\Enums\Review;

enum Status:int
{
    case NEW = 0;
    case ACCEPT = 1; 
    case DECLINE = 2;
}