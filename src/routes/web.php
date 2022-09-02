<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/chats/{chat_id}/timeline',function($chat_id){
    return view('chats.chats_timeline',compact('chat_id'));
});

Route::post('/chats/{chat_id}/timeline',function($chat_id){
    return view('chats.chats_timeline',compact('chat_id'));
    //一旦view直接返す
})->name('reply_to_chats');

Route::get('/boards/trade/timeline',function(){
    return view('trade_board.trade_board_timeline');
});

Route::post('/boards/trade/timeline',function(){
    return view('trade_board.trade_board_timeline');
})->name('post_to_trade_board');

Route::get('/boards/trade/thread/{parent_trade_post_id}',function(){
    return view('trade_board.trade_board_thread');
})->name('trade_board_thread');

require __DIR__.'/auth.php';
