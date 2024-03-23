<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserRole;
use App\Facades\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::load(['role' => UserRole::ADMIN->value, 'super_admin' => true]);

        $this->call([
            DocumentTypesSeeder::class,
            SuperAdminSeeder::class,
            UbigeoSeeder::class,
        ]);
    }
}
