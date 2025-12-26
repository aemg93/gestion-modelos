<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platform;

class PlatformsSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['name' => 'Chaturbate', 'description' => 'Streaming en vivo'],
            ['name' => 'Stripchat',  'description' => 'Plataforma de shows en vivo'],
            ['name' => 'Cams',       'description' => 'Sitio de transmisión para modelos'],
            ['name' => 'LoyalFans',  'description' => 'Contenido exclusivo para fans'],
        ];

        foreach ($platforms as $platform) {
            Platform::firstOrCreate(['name' => $platform['name']], $platform);
        }
    }
}
