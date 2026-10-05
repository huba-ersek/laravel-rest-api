<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\County;

class CountySeeder extends Seeder
{
    private static $countyData = [
        [
            'name' => "Bács-Kiskun",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/bacs.jpg"
        ],
        [
            'name' => "Baranya",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/baranya_0.jpg"
        ],
        [
            'name' => "Békés",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/bekes.jpg"
        ],
        [
            'name' => "Borsod-Abaúj-Zemplén",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/borsod.jpg"
        ],
        [
            'name' => "Budapest",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/budapest.jpg"
        ],
        [
            'name' => "Csongrád-Csanád",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/csongrad.jpg"
        ],
        [
            'name' => "Fejér",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/fejer.jpg"
        ],
        [
            'name' => "Győr-Moson-Sopron",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/gyor.jpg"
        ],
        [
            'name' => "Hajdú-Bihar",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/hajdu.jpg"
        ],
        [
            'name' => "Heves",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/heves.jpg"
        ],
        [
            'name' => "Jász-Nagykun-Szolnok",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/jasz.jpg"
        ],
        [
            'name' => "Komárom-Esztergom",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/komarom.jpg"
        ],
        [
            'name' => "Nógrád",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/nograd.jpg"
        ],
        [
            'name' => "Pest",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/pest.jpg"
        ],
        [
            'name' => "Somogy",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/somogy.jpg"
        ],
        [
            'name' => "Szabolcs-Szatmár-Bereg",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/szabolcs.jpg"
        ],
        [
            'name' => "Tolna",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/tolna.jpg"
        ],
        [
            'name' => "Vas",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/vas.jpg"
        ],
        [
            'name' => "Veszprém",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/veszprem.jpg"
        ],
        [
            'name' => "Zala",
            'link' => "https://www.nemzetijelkepek.hu/sites/default/files/styles/large/public/2022-06/zala.jpg"
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::$countyData as $data)
        {
            County::insert([
                'name' => $data['name'],
                'arms' => $data['link']
            ]);
        }
    }
}
