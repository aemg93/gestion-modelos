<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkHour;

class WorkHourSeeder extends Seeder
{
    public function run()
    {
        WorkHour::create([
            'model_profile_id' => 1,
            'date' => now()->subDays(2)->toDateString(), // 👈 agregado
            'hours' => 5,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        WorkHour::create([
            'model_profile_id' => 1,
            'date' => now()->subDay()->toDateString(),   // 👈 agregado
            'hours' => 8,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
    }
}
