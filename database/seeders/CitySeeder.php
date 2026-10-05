<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(__DIR__ . "/cities.csv", "r");
        if (!$file) return;
        $lines = self::countLines($file);
        $bar = $this->command->getOutput()->createProgressBar($lines - 1);
        $bar->start();
        fseek($file, 0);
        fgetcsv($file);
        $values = fgetcsv($file);
        for (; $values; $values = fgetcsv($file))
        {
            City::insert([
                'id' => $values[0],
                'zip_code' => $values[1],
                'city' => $values[2],
                'county_id' => $values[3],
                'population' => rand(300, 1000000)
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
        fclose($file);
    }

    private static function countLines($file)
    {
        $lines = 0;
        while (!feof($file)) {
            $lines += substr_count(fread($file, 8192), "\n");
        }
        return $lines;
    }
}
