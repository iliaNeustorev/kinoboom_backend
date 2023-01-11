<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RootUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'Guest',
            'status' => 1,
            'email' => 'guest@yandex.ru',
            'email_verified_at' => now(),
            'password' =>  Hash::make('admin')
        ];

        ModelsUser::create($data);
    }
}
