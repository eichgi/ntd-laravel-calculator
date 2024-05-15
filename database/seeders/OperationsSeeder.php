<?php

namespace Database\Seeders;

use App\Http\enums\operations;
use App\Models\Operation;
use Illuminate\Database\Seeder;

class OperationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = [
            [
                'type' => operations::ADDITION,
                'cost' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => operations::SUBTRACTION,
                'cost' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => operations::MULTIPLICATION,
                'cost' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => operations::DIVISION,
                'cost' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => operations::SQUARE_ROOT,
                'cost' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => operations::RANDOM_STRING,
                'cost' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Operation::insert($operations);
    }
}
