<?php

namespace Database\Factories;

use App\Models\TradeBoardPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TradeBoardPost>
 */
class TradeBoardPostFactory extends Factory
{
    protected $model=TradeBoardPost::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {   
        $null_or_random_number_array=[null,$this->faker->numberBetween(1,10)];
        $parent_trade_board_post_id=$null_or_random_number_array[$this->faker->numberBetween(0,1)];
        if($parent_trade_board_post_id===null){
            $depth=0;
            $is_only_description=0;
        }else{
            $depth=$this->faker->numberBetween(1,2);
            $is_only_description=$this->faker->boolean(50);
        }
        $all_user_id_array=User::pluck('id');
        return [
            'user_id'=>$this->faker->randomElement($all_user_id_array),
            'description'=>$this->faker->realText(50),
            'parent_trade_board_post_id'=>$parent_trade_board_post_id,
            'allow_show_pad_id_bool'=>$this->faker->boolean(50),
            'depth'=>$depth,
            'is_only_description'=>$is_only_description,
            'updated_at'=>null,
            'created_at'=>$this->faker->dateTimeBetween('-2 week','now')
        ];
    }
}
