<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        DB::table('gender')->insert([
            'name' => 'Laki - Laki',
            'type' => 0,
        ]);
        DB::table('gender')->insert([
            'name' => 'Perempuan',
            'type' => 0,
        ]);
        DB::table('gender')->insert([
            'name' => 'Pria',
            'type' => 1,
        ]);
        DB::table('gender')->insert([
            'name' => 'Wanita',
            'type' => 1,
        ]);
    }
}
