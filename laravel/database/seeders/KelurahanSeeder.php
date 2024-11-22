<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
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

        foreach ($provs as $prov) {
            $jsonPath = database_path("seeders/wilayah_idn/kabupaten/$prov[id].json");
            $jsonDataKabs = File::get($jsonPath);
            $kabs = json_decode($jsonDataKabs, true);

            foreach ($kabs as $kab) {
                $jsonPath = database_path("seeders/wilayah_idn/kecamatan/$kab[id].json");
                $jsonDataKecs = File::get($jsonPath);
                $kecs = json_decode($jsonDataKecs, true);

                foreach ($kecs as $kec) {
                    $jsonPath = database_path("seeders/wilayah_idn/kelurahan/$kec[id].json");
                    $jsonDataKels = File::get($jsonPath);
                    $kels = json_decode($jsonDataKels, true);

                    $arryDatas = [];
                    foreach ($kels as $kel) {
                        array_push($arryDatas, ['id' => $kel['id'], 'name' => $kel['nama'], 'id_kecamatan' => $kec['id']]);
                    }

                    DB::table('kelurahan')->insert($arryDatas);
                }
            }
        }
    }
}
