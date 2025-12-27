<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Earning;

class EarningSeeder extends Seeder
{
    public function run()
    {
        Earning::create([
            'model_profile_id' => 1,   // 👈 corregido
            'amount' => 150.50,
            'date' => now()->subDays(2)->toDateString(),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        Earning::create([
            'model_profile_id' => 1,
            'amount' => 200.00,
            'date' => now()->subDay()->toDateString(),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
    }
}
