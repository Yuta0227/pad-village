<?php

namespace Database\Seeders;

use App\Models\TradePostRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TradePostRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TradePostRequest::factory()->count(50)->create();
    }
}
