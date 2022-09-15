<?php

namespace App\Http\Controllers;

use App\Models\TradeBoardPost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TradeBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts=TradeBoardPost::posts_for_timeline()->with('trade_post_gives')->with('trade_post_requests')->with('user')->get()->groupBy(function($row){
            return $row->created_at->format('Y年m月d日');
        });
        // foreach($posts as &$post){
        //     //インスタンス生成
        //     $created_at=new Carbon($post->created_at);
        //     //時：分にformat
        //     $formatted_created_at=$created_at->format('H:i');
        //     //反映
        //     $post->created_at=$formatted_created_at;
        // }
        return view('/trade_board/trade_board_timeline',compact('posts'));
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
        //
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('/trade_board/trade_board_thread',['id'=>$id]);
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
