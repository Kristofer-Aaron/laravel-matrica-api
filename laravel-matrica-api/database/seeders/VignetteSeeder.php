<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VignetteSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = DB::table('vehicles')->get();

        $types = ['D1', 'D2', 'U'];
        $categories = ['Éves', 'Megyei', 'Heti', 'Havi'];
        $regions = ['Pest', 'Fejér', 'Győr-Moson-Sopron', 'Borsod', 'Baranya', null];

        foreach ($vehicles as $vehicle) {

            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {

                $category = $categories[array_rand($categories)];
                $region = $category === 'Megyei'
                    ? $regions[array_rand($regions)]
                    : null;

                $year = rand(2024, 2026);

                // Éves és megyei matricák érvényessége: következő év jan 31. 23:59:59
                if (in_array($category, ['Éves', 'Megyei'])) {
                    $validFrom = Carbon::create($year, 1, 1, 0, 0, 0);
                    $validTo = Carbon::create($year + 1, 1, 31, 23, 59, 59);
                } else {
                    // Heti / Havi matricák random érvényességgel
                    $validFrom = now()->subDays(rand(0, 300));
                    $validTo = (clone $validFrom)->addDays(rand(7, 30));
                }

                DB::table('vignettes')->insert([
                    'vehicle_id' => $vehicle->id,
                    'type' => $types[array_rand($types)],
                    'category' => $category,
                    'region' => $region,
                    'year' => $year,
                    'valid_from' => $validFrom,
                    'valid_to' => $validTo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
