<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            PlatformsSeeder::class,
            ModelProfileSeeder::class,
            EarningSeeder::class,
            WorkHourSeeder::class,
        ]);
    }
}
