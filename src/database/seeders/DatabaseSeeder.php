<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TradeBoardPostsTableSeeder::class);
        $this->call(TradePostRequestsTableSeeder::class);
        $this->call(TradePostGivesTableSeeder::class);
    }
}
