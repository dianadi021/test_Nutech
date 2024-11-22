<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        DB::table('penduduk')->insert([
            'fullname' => 'root',
            'created_at' => now('Asia/Jakarta')
        ]);

        $id_penduduk = DB::table('penduduk')->max('id');

        DB::table('users')->insert([
            'username' => 'root',
            'email' => 'root@skuad.com',
            'status' => 1,
            'id_role' => 1,
            'password' => Hash::make('1234'),
            'created_at' => now('Asia/Jakarta')
        ]);

        $id_user = DB::table('penduduk')->max('id');

        DB::table('pegawai')->insert([
            'id_user' => $id_user,
            'id_penduduk' => $id_penduduk,
            'created_at' => now('Asia/Jakarta')
        ]);
    }
}
