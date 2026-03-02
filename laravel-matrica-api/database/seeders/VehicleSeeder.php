<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['country_code' => 'H', 'plate_number' => 'AAA001'],
            ['country_code' => 'H', 'plate_number' => 'BBB234'],
            ['country_code' => 'H', 'plate_number' => 'CCC567'],
            ['country_code' => 'H', 'plate_number' => 'DDD890'],
            ['country_code' => 'H', 'plate_number' => 'EEE123'],
            ['country_code' => 'H', 'plate_number' => 'FFF456'],
            ['country_code' => 'H', 'plate_number' => 'GGG789'],
            ['country_code' => 'H', 'plate_number' => 'HHH321'],
            ['country_code' => 'H', 'plate_number' => 'III654'],
            ['country_code' => 'H', 'plate_number' => 'JJJ987'],
        ];

        foreach ($vehicles as $v) {
            DB::table('vehicles')->insert([
                'country_code' => $v['country_code'],
                'plate_number' => $v['plate_number'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
