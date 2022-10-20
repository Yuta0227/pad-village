<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    use HasFactory;
    public function trade_post_requests(){
        return $this->hasMany(TradePostRequest::class);
    }
    public function trade_post_gives(){
        return $this->hasMany(TradePostGive::class);
    }
    //モンポ100未満のモンスターを返す（これは今後実装）
    public static function tradable_monsters(){
        $tradable_monster_name_array = Monster::pluck('name')->toArray();
        array_push($tradable_monster_name_array, 'プラスポイント297');
        array_push($tradable_monster_name_array, 'ノエル何色でも');
        return $tradable_monster_name_array;
    }
}
