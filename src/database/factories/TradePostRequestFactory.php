<?php

namespace Database\Factories;

use App\Models\Monster;
use App\Models\TradeBoardPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TradePostRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $all_monster_name_array=Monster::pluck('name')->toArray();
        array_push($all_monster_name_array,'プラスポイント');
        array_push($all_monster_name_array,'ノエル何色でも');
        $trade_board_posts=TradeBoardPost::where('is_only_description',0)->pluck('id');
        return [
            'trade_board_post_id'=>$this->faker->randomElement($trade_board_posts),
            'monster_name'=>$this->faker->randomElement($all_monster_name_array),
            'monster_amount'=>$this->faker->numberBetween(1,10)
        ];
    }
}