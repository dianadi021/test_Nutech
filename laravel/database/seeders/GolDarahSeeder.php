<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GolDarahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        DB::table('golongan_darah')->insert([
            'name' => 'A',
        ]);
        DB::table('golongan_darah')->insert([
            'name' => 'B',
        ]);
        DB::table('golongan_darah')->insert([
            'name' => 'AB',
        ]);
        DB::table('golongan_darah')->insert([
            'name' => 'O',
        ]);
    }
}
