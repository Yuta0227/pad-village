<?php

namespace App\Http\Controllers;

use App\Models\TradeBoardPost;
use App\Models\TradePostGive;
use App\Models\TradePostRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

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
        $previous_url = app('url')->previous();
        $this->save_entered_data_to_session($request);
        if (!Auth::check()) {
            //ログインページに遷移させるとりあえずもとにもどしてる
            $errors = new MessageBag();
            $errors->add('', 'ログインしてから投稿してください');
            return redirect($previous_url)->withErrors($errors);
        }
        $restrict_only_description = $request->depth == 0;
        //タイムラインの投稿の場合
        if ($restrict_only_description) {
            //求のvalidation
            $monster_requests_without_null = $this->validate_post_or_negotiation_monster_request_and_return_collection($request);
            //出のvalidation
            $monster_gives_without_null = $this->validate_post_or_negotiation_monster_give_and_return_collection($request);
            //出・求両方未入力の場合
            $both_monster_requests_and_monster_gives_are_empty = count($monster_requests_without_null) === 0 && count($monster_gives_without_null) === 0;
            if ($both_monster_requests_and_monster_gives_are_empty) {
                $errors = new MessageBag();
                $errors->add('', '出・求が両方とも空です');
                return redirect($previous_url)->withErrors($errors);
            }
            $post_id = $this->insert_into_trade_board_posts_and_return_id($request);
            $this->insert_into_trade_post_requests($post_id, $monster_requests_without_null);
            $this->insert_into_trade_post_gives($post_id, $monster_gives_without_null);
            $this->delete_entered_data_from_session();
        } else {
            //返信
            //交渉の場合validation
            $post_is_not_only_description=$this->post_is_not_only_description($request);
            if ($post_is_not_only_description) {
                //求のvalidation
                $monster_requests_without_null = $this->validate_post_or_negotiation_monster_request_and_return_collection($request);
                //出のvalidation
                $monster_gives_without_null = $this->validate_post_or_negotiation_monster_give_and_return_collection($request);
                //出・求両方未入力の場合
                $both_monster_requests_and_monster_gives_are_empty = count($monster_requests_without_null) === 0 && count($monster_gives_without_null) === 0;
                if ($both_monster_requests_and_monster_gives_are_empty) {
                    $errors = new MessageBag();
                    $errors->add('', '出・求が両方とも空です');
                    return redirect($previous_url)->withErrors($errors);
                }
            }
            $post_id = $this->insert_into_trade_board_posts_and_return_id($request);
            if ($post_is_not_only_description) {
                $this->insert_into_trade_post_requests($post_id, $monster_requests_without_null);
                $this->insert_into_trade_post_gives($post_id, $monster_gives_without_null);
            }
            $this->delete_entered_data_from_session();
        }
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

    /**
     * Insert data into trade_post_requests table
     * 
     * @param int $trade_board_post_id
     * @param collection $monster_requests
     */
    public function insert_into_trade_post_requests($trade_board_post_id, $monster_requests)
    {
        foreach ($monster_requests as $monster_request) {
            $trade_post_request = new TradePostRequest;
            $trade_post_request->trade_board_post_id = $trade_board_post_id;
            $trade_post_request->monster_name = $monster_request['name'];
            $trade_post_request->monster_amount = $monster_request['amount'];
            $trade_post_request->save();
        }
    }

    /**
     * Insert data into trade_post_gives table
     * 
     * @param int $trade_board_post_id
     * @param collection $monster_gives
     */
    public function insert_into_trade_post_gives($trade_board_post_id, $monster_gives)
    {
        foreach ($monster_gives as $monster_give) {
            $trade_post_give = new TradePostGive;
            $trade_post_give->trade_board_post_id = $trade_board_post_id;
            $trade_post_give->monster_name = $monster_give['name'];
            $trade_post_give->monster_amount = $monster_give['amount'];
            $trade_post_give->save();
        }
    }

    /**
     * save entered data to session
     * @param \Illuminate\Http\Request $request
     */
    public function save_entered_data_to_session($request)
    {
        session()->put('monster_requests', $request->monster_requests);
        session()->put('monster_gives', $request->monster_gives);
        session()->put('description', $request->description);
        session()->put('allow_show_pad_id_bool', $request->allow_show_pad_id_bool);
    }

    /**
     * delete entered data from session
     */
    public function delete_entered_data_from_session()
    {
        session()->forget('monster_requests');
        session()->forget('monster_gives');
        session()->forget('description');
        session()->forget('allow_show_pad_id_bool');
    }

    /**
     * validate entered data for post or negotiation monster request
     * 
     */
    public function validate_post_or_negotiation_monster_request_and_return_collection($request)
    {
        $monster_requests_without_null = [];
        foreach ($request->monster_requests as $monster_request_key => $monster_request) {
            //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
            $only_monster_amount_is_filled_out = empty($monster_request['name']) && !empty($monster_request['amount']);
            $only_monster_name_is_filled_out = !empty($monster_request['name']) && empty($monster_request['amount']);
            $name_and_amount_are_null = empty($monster_request['name']) && empty($monster_request['amount']);
            //片方のみ入力が一個でもある時点でformに戻る
            if ($only_monster_amount_is_filled_out || $only_monster_name_is_filled_out) {
                $request->validate([
                    'monster_requests.' . $monster_request_key . '.name' => ['required'],
                    'monster_requests.' . $monster_request_key . '.amount' => ['required'],
                ], [
                    'monster_requests.' . $monster_request_key . '.name.required' => '求のモンスター名が未入力です',
                    'monster_requests.' . $monster_request_key . '.amount.required' => '求の個数が未入力です',
                ]);
            }
            if (!$name_and_amount_are_null) {
                array_push($monster_requests_without_null, $monster_request);
            }
        }
        return $monster_requests_without_null;
    }
    /**
     * validate entered data for post or negotiation monster give
     * 
     */
    public function validate_post_or_negotiation_monster_give_and_return_collection($request)
    {
        $monster_gives_without_null = [];
        foreach ($request->monster_gives as $monster_give_key => $monster_give) {
            //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
            $only_monster_amount_is_filled_out = empty($monster_give['name']) && !empty($monster_give['amount']);
            $only_monster_name_is_filled_out = !empty($monster_give['name']) && empty($monster_give['amount']);
            $name_and_amount_are_null = empty($monster_give['name']) && empty($monster_give['amount']);
            //片方のみ入力が一個でもある時点でformに戻る
            if ($only_monster_amount_is_filled_out || $only_monster_name_is_filled_out) {
                $request->validate([
                    'monster_gives.' . $monster_give_key . '.name' => ['required'],
                    'monster_gives.' . $monster_give_key . '.amount' => ['required']
                ], [
                    'monster_gives.' . $monster_give_key . '.name.required' => '出のモンスター名が未入力です',
                    'monster_gives.' . $monster_give_key . '.amount.required' => '出の個数が未入力です'
                ]);
            }
            if (!$name_and_amount_are_null) {
                array_push($monster_gives_without_null, $monster_give);
            }
        }
        return $monster_gives_without_null;
    }

    /**
     * insert into trade_board_posts table
     * @param \Illuminate\Http\Request $request
     */
    public function insert_into_trade_board_posts_and_return_id($request)
    {
        $post = new TradeBoardPost;
        $post->user_id = Auth::id();
        $post->description = $request->description;
        $post->parent_trade_board_post_id = $request->parent_trade_board_post_id;
        $post->allow_show_pad_id_bool = ($request->allow_show_pad_id_bool === 'on') ? 1 : 0;
        $post->depth = $request->depth;
        $post->save();
        return $post->id;
    }

    /**
     * check if post is not only description in thread
     * @param \Illuminate\Http\Request $request
     */
    public function post_is_not_only_description($request){
        $monster_requests=$request->monster_requests;
        $monster_gives=$request->monster_gives;
        foreach($monster_requests as $key=>$monster_request){
            if($monster_request['name']==null&&$monster_request['amount']==null){
                unset($monster_requests[$key]);
            }
        }
        foreach($monster_gives as $key=>$monster_give){
            if($monster_give['name']==null&&$monster_give['amount']==null){
                unset($monster_gives[$key]);
            }
        }
        if(count($monster_requests)==0&&count($monster_gives)==0){
            return false;
        }else{
            return true;
        }
    }
}
