<?php

namespace Database\Factories;

use App\Models\Monster;
use App\Models\TradeBoardPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradePostGive>
 */
class TradePostGiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $all_monster_name_array=Monster::tradable_monsters();
        $trade_board_posts=TradeBoardPost::where('depth',0)->pluck('id');
        return [
            'trade_board_post_id'=>$this->faker->randomElement($trade_board_posts),
            'monster_name'=>$this->faker->randomElement($all_monster_name_array),
            'monster_amount'=>$this->faker->numberBetween(1,10)
        ];
    }
}
