<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultAdmin extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'developer',
                'email' => 'devsoft7pm@gmail.com',
                'password' => Hash::make('!@admin123'),
                'petname'=>'romie',
                'breed'=>'desi',
            ],
        ];

        DB::table('users')->insertOrIgnore($data);
    }
}

