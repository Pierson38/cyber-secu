<?php

namespace Database\Seeders;

use App\Models\RoleEnum;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($_ = 0; $_ < 50; $_++) {
            $roleIndex = array_rand([
                \str(RoleEnum::TECHNICIEN->name),
                \str(RoleEnum::CONSULTANT->name),
                \str(RoleEnum::COMMERCIAL->name)
            ]);
            User::factory()->create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'role' => RoleEnum::cases()[$roleIndex]->name
            ]);
        }

    }
}
