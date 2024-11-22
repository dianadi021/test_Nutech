<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');

        $jsonPath = database_path('seeders/wilayah_idn/provinsi.json');
        $jsonData = File::get($jsonPath);
        $provs = json_decode($jsonData, true);

        $arryDatas = [];
        foreach ($provs as $prov) {
            array_push($arryDatas, ['id' => $prov['id'], 'name' => $prov['nama']]);
        }

        DB::table('provinsi')->insert($arryDatas);
    }
}
