<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradePostRequest extends Model
{
    use HasFactory;
    protected $table = 'trade_post_requests';
    protected $guarded = array('id');
    public $timestamps = false;
    protected $fillable = [
        'trade_board_post_id', 'monster_id','monster_amount'
    ];
    public function trade_board_post(){
        return $this->belongsTo(TradeBoardPost::class);
    }
    public function monster(){
        return $this->belongsTo(Monster::class);
    }
}
