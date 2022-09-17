<?php

namespace App\Http\Controllers;

use App\Models\TradeBoardPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TradeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = TradeBoardPost::posts_for_timeline()->with('trade_post_gives')->with('trade_post_requests')->with('user')->get();
        return view('/trade_board/trade_board_timeline', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//js出すの方で同じことをする
//一つの組み合わせで片方入力があった場合validationかけてあるはず
//求出両方一つも入力なかったらはじく


        //投稿または交渉の場合
        if ($request->is_only_description == 0) {
            //求のvalidation
            //求出の両方未入力の場合どうするか
            $request_null_count=0;
            $give_null_count=0;
            foreach ($request->monster_requests as $monster_request_key=>$monster_request) {
                //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
                $only_monster_amount_is_filled_out = empty($monster_request['name']) && !empty($monster_request['amount']);
                $only_monster_name_is_filled_out = !empty($monster_request['name']) && empty($monster_request['amount']);
                $name_and_amount_are_null = empty($monster_request['name']) && empty($monster_request['amount']);
                //片方のみ入力が一個でもある時点でformに戻る
                if ($only_monster_amount_is_filled_out || $only_monster_name_is_filled_out) {
                    $request->validate([
                        'monster_requests.'.$monster_request_key.'.name' => ['required'],
                        'monster_requests.'.$monster_request_key.'.amount' => ['required'],
                    ], [
                        'monster_requests.'.$monster_request_key.'.name.required' => '求のモンスター名が未入力です',
                        'monster_requests.'.$monster_request_key.'.amount.required' => '求の個数が未入力です',
                    ]);
                }
                if($name_and_amount_are_null){
                    $request_null_count++;
                }
            }
            // dd($request_null_count);
            //出のvalidation
            foreach ($request->monster_gives as $monster_give) {
                //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
                $only_monster_amount_is_filled_out = empty($monster_give['name']) && !empty($monster_give['amount']);
                $only_monster_name_is_filled_out = !empty($monster_give['name']) && empty($monster_give['amount']);
                $name_and_amount_are_null = empty($monster_give['name']) && empty($monster_give['amount']);
                //片方のみ入力が一個でもある時点でformに戻る
                if ($only_monster_amount_is_filled_out || $only_monster_name_is_filled_out) {
                    $request->validate([
                        'monster_gives.*.name' => ['required'],
                        'monster_gives.*.amount' => ['required']
                    ], [
                        'monster_gives.*.name.required' => '出のモンスター名が未入力です',
                        'monster_gives.*.amount.required' => '出の個数が未入力です'
                    ]);
                }
                if($name_and_amount_are_null){
                    $give_null_count++;
                }
            }
            // dd($request);
            //出・求両方未入力の場合
            if(count($request->monster_requests)-$request_null_count===0&&count($request->monster_gives)-$give_null_count===0){
                // dd($request);
                //errorを投げる関数
                //はじく
            }
        }else{
            //返信
        }
        $post = new TradeBoardPost;
        $post->user_id = Auth::id();
        $post->description = $request->description;
        $post->parent_trade_board_post_id = $request->parent_trade_board_post_id;
        $post->allow_show_pad_id_bool = ($request->allow_show_pad_id_bool === 'on') ? 1 : 0;
        $post->depth = $request->depth;
        $post->is_only_description = $request->is_only_description;
        $post->save();
        // $post->created_at=Carbon::now();
        // $post->updated_at=null;
        $previous_url = app('url')->previous();
        return redirect()->to($previous_url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = TradeBoardPost::with('trade_post_gives')->with('trade_post_requests')->with('user')->find($id);
        $replies = TradeBoardPost::replies_for_post($id)->with('trade_post_gives')->with('trade_post_requests')->with('user')->get();
        return view('/trade_board/trade_board_thread', compact('post', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
