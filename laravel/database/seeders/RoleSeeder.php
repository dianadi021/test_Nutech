<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        $arryDatas = [
            [
                'name' => 'root',
                'level' => 0,
                'description' => 'Merupakan role DevOps/Admin Server',
            ],
            [
                'name' => 'developer',
                'level' => 1,
                'description' => 'Merupakan role Developer/Programmer app',
            ],
            [
                'name' => 'superadministrator',
                'level' => 2,
                'description' => null,
            ],
            [
                'name' => 'administrator',
                'level' => 3,
                'description' => null,
            ],
            [
                'name' => 'admin',
                'level' => 4,
                'description' => null,
            ],
            [
                'name' => 'client',
                'level' => 5,
                'description' => null,
            ],
            [
                'name' => 'moderator',
                'level' => 6,
                'description' => null,
            ],
            [
                'name' => 'supporter',
                'level' => 7,
                'description' => null,
            ],
            [
                'name' => 'member',
                'level' => 8,
                'description' => null,
            ]
        ];

        DB::table('roles')->insert($arryDatas);
    }
}
