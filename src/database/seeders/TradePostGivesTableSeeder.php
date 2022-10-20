<?php

namespace Database\Seeders;

use App\Models\TradePostGive;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TradePostGivesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TradePostGive::factory()->count(50)->create();
    }
}
