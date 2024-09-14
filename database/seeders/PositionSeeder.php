<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
         // Check if 'CEO' already exists
         $ceo = Position::firstOrCreate([
            'name' => 'CEO',
        ], [
            'reports_to' => null,
        ]);

        // Check if 'Manager' already exists
        $manager = Position::firstOrCreate([
            'name' => 'Manager',
        ], [
            'reports_to' => $ceo->id,
        ]);

    }
}
