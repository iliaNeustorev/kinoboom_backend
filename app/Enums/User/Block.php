<?php 
namespace App\Enums\User;

enum Block:int
{
    case UNBLOCK = 0;
    case BLOCK = 1;
}