<?php

namespace Database\Seeders;

use App\Models\TradeBoardPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TradeBoardPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       TradeBoardPost::factory()->count(20)->create();
    }
}
