<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ModelProfile;
use App\Models\Platform;
use App\Models\Earning;
use App\Models\WorkHour;

class ModelProfileSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            ['name' => 'Ana Torres', 'nickname' => 'AnaStar', 'email' => 'ana@example.com'],
            ['name' => 'Laura Gómez', 'nickname' => 'LauG', 'email' => 'laura@example.com'],
            ['name' => 'Maria Melendis', 'nickname' => 'Maria', 'email' => 'maria@example.com'],
            ['name' => 'Dayana Martines', 'nickname' => 'Dayana', 'email' => 'dayana@example.com'],
        ];

        $platforms = Platform::all();

        foreach ($models as $data) {
            // Crear usuario
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                ]
            );
            $user->assignRole('Modelo');

            // Crear perfil y asociar usuario
            $profile = ModelProfile::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nickname' => $data['nickname'],
                ]
            );
            $profile->user()->associate($user);
            $profile->save();

            // Vincular plataformas aleatorias
            if ($platforms->count() > 0) {
                $profile->platforms()->sync($platforms->pluck('id')->random(min(2, $platforms->count())));
            }

            // Crear ingresos y horas de prueba
            Earning::create([
                'model_profile_id' => $profile->id,
                'amount' => rand(100, 300),
                'date' => now()->subDays(rand(1, 5)),
            ]);

            WorkHour::create([
                'model_profile_id' => $profile->id,
                'date' => now()->subDays(rand(1, 5)),
                'hours' => rand(4, 8),
            ]);
        }
    }
}
