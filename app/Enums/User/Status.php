<?php 
namespace App\Enums\User;

enum Status:int
{
    case REGISTER = 0;
    case VERIFIED = 1;

    public function text(){
        return match($this->value){
            self::REGISTER->value => 'Зарегистрирован',
            self::VERIFIED->value => 'Верифицирован',
        };
    }
}