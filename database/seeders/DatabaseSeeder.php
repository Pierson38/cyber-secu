<?php

namespace Database\Seeders;

use App\Models\RoleEnum;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'nom.prenom@gmail.com',
            'password' => 'b06CN5CJdNwDrSf',
            'role' => RoleEnum::TECHNICIEN->name
        ]);
        User::factory()->create([
            'name' => 'Technicien User',
            'email' => 'technicien@gmail.com',
            'password' => '12345678910',
            'role' => RoleEnum::TECHNICIEN->name
        ]);
        User::factory()->create([
            'name' => 'Consultant User',
            'email' => 'consultant@gmail.com',
            'password' => '12345678910',
            'role' => RoleEnum::CONSULTANT->name
        ]);
        User::factory()->create([
            'name' => 'Commercial User',
            'email' => 'commercial@gmail.com',
            'password' => '12345678910',
            'role' => RoleEnum::COMMERCIAL->name
        ]);
        User::factory()->create([
            'name' => 'Administrateur User',
            'email' => 'admin@gmail.com',
            'password' => '12345678910',
            'role' => RoleEnum::ADMINISTRATEUR->name
        ]);

        User::factory()->create([
            'name' => 'Jean-Marc de Pic à Aule',
            'email' => 'jean-marc.picaule@gmail.com',
            'password' => 'dijon21000',
            'role' => RoleEnum::ADMINISTRATEUR->name
        ]);


    }
}
