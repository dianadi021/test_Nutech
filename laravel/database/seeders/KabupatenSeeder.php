<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KabupatenSeeder extends Seeder
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
            $jsonPath = database_path("seeders/wilayah_idn/kabupaten/$prov[id].json");
            $jsonDataKabs = File::get($jsonPath);
            $kabs = json_decode($jsonDataKabs, true);

            foreach ($kabs as $kab) {
                $tmpNama = explode("KAB. ", $kab['nama']);
                $tmpNama = (empty($tmpNama[1]) ? explode("KOTA ", $kab['nama']) : $tmpNama);
                $tmpNama = (empty($tmpNama[1]) ? explode("KAB ", $kab['nama']) : $tmpNama);

                array_push($arryDatas, ['id' => $kab['id'], 'name' => $tmpNama[1], 'id_provinsi' => $prov['id']]);
            };
        }

        DB::table('kabupaten')->insert($arryDatas);
    }
}
