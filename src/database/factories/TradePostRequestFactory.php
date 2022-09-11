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
        $all_monster_id_array=Monster::pluck('id');
        $trade_board_posts_not_reply=TradeBoardPost::where('is_reply',0)->pluck('id');
        return [
            'trade_board_post_id'=>$this->faker->randomElement($trade_board_posts_not_reply),
            'monster_id'=>$this->faker->randomElement($all_monster_id_array),
            'monster_amount'=>$this->faker->numberBetween(1,10)
        ];
    }
}
