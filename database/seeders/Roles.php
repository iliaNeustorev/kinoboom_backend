<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsRole::create(['name' => 'admin', 'description' => 'Администратор сайта']);
        ModelsRole::create(['name' => 'moderator', 'description' => 'Модератор коментариев']);
        ModelsRole::create(['name' => 'user', 'description' => 'Пользователь']);
    }
}
