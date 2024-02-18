<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bronze - 10 usuários
        Plan::create([
            'description' => 'BRONZE',
            'limit' => 10,
        ]);

        // Prata - 20 usuários
        Plan::create([
            'description' => 'PRATA',
            'limit' => 20,
        ]);

        // Ouro - Ilimitado
        Plan::create([
            'description' => 'OURO',
            'limit' => null, // null para indicar ilimitado
        ]);
    }
}
