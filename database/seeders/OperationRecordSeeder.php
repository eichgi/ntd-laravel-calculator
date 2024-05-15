<?php

namespace Database\Seeders;

use App\Models\Record;
use Illuminate\Database\Seeder;

class OperationRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Record::factory(100)->create([
            'user_balance' => 100,
        ]);
    }
}
