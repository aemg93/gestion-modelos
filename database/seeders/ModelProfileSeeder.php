<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelProfile;
use App\Models\Platform;
use App\Models\Earning;
use App\Models\WorkHour;

class ModelProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Crear algunos perfiles de modelos
        $model1 = ModelProfile::create([
            'name' => 'Ana Torres',
            'nickname' => 'AnaStar',
            'email' => 'ana@example.com',
        ]);

        $model2 = ModelProfile::create([
            'name' => 'Laura Gómez',
            'nickname' => 'LauG',
            'email' => 'laura@example.com',
        ]);

        // Vincularlos a plataformas existentes
        $platforms = Platform::all();
        $model1->platforms()->attach($platforms->pluck('id')->random(2));
        $model2->platforms()->attach($platforms->pluck('id')->random(2));

        // Crear ingresos de prueba
        Earning::create([
            'model_profile_id' => $model1->id,
            'amount' => 150.50,
            'date' => now()->subDays(2),
        ]);

        Earning::create([
            'model_profile_id' => $model2->id,
            'amount' => 200.00,
            'date' => now()->subDay(),
        ]);

        // Crear horas de trabajo de prueba
        WorkHour::create([
            'model_profile_id' => $model1->id,
            'date' => now()->subDays(2),
            'hours' => 5,
        ]);

        WorkHour::create([
            'model_profile_id' => $model2->id,
            'date' => now()->subDay(),
            'hours' => 6,
        ]);
    }
}
