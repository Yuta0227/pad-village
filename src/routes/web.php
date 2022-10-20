<?php

use App\Http\Controllers\TradeBoardController;
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
    return redirect('/boards/trade');
});

Route::get('/chats/{chat_id}/timeline', function ($chat_id) {
    return view('chats.chats_timeline', compact('chat_id'));
});

Route::post('/chats/{chat_id}/timeline', function ($chat_id) {
    return view('chats.chats_timeline', compact('chat_id'));
    //一旦view直接返す
})->name('reply_to_chats');

Route::resource('/boards/trade', 'TradeBoardController');





require __DIR__ . '/auth.php';
