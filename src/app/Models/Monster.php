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
}
