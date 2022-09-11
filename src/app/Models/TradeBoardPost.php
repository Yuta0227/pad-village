<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeBoardPost extends Model
{
    use HasFactory;
    protected $table = 'trade_board_posts';
    protected $guarded = array('id');
    public $timestamps = true;
    protected $fillable = [
        'user_id', 'description', 'parent_trade_board_post_id', 'allow_show_pad_id_bool', 'depth', 'is_reply','created_at','updated_at'
    ];
}
