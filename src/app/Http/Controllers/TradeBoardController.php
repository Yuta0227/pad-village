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
        if (session()->has('monster_requests')) {
            $old_monster_requests = $this->format_monster_request_post(session()->get('monster_requests'));
            if (empty($old_monster_requests)) {
                //form送信時空っぽだとinputが消えてしまうため最低空のinputが一個あるようにする
                $old_monster_requests = [
                    ['name' => '', 'amount' => '']
                ];
            }
        } else {
            $old_monster_requests = [
                ['name' => '', 'amount' => '']
            ];
        }
        if (session()->has('monster_gives')) {
            $old_monster_gives = $this->format_monster_give_post(session()->get('monster_gives'));
            //form送信時空っぽだとinputが消えてしまうため最低空のinputが一個あるようにする
            if (empty($old_monster_gives)) {
                $old_monster_gives = [
                    ['name' => '', 'amount' => '']
                ];
            }
        } else {
            $old_monster_gives = [
                ['name' => '', 'amount' => '']
            ];
        }
        $posts = TradeBoardPost::posts_for_timeline()->with('trade_post_gives')->with('trade_post_requests')->with('user')->get();
        return view('/trade_board/trade_board_timeline', compact('posts', 'old_monster_requests', 'old_monster_gives'));
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
        session()->flash('modal_is_open', true);
        $previous_url = app('url')->previous();
        $this->save_entered_data_to_session($request);
        if (!Auth::check()) {
            //ログインページに遷移させるとりあえずもとにもどしてる
            $errors = new MessageBag();
            $errors->add('', '村に入ってから投稿してください');
            session()->put('open_modal', true);
            return redirect($previous_url)->withErrors($errors);
        }
        $restrict_only_description = $request->depth == 0;
        //タイムラインの投稿の場合
        if ($restrict_only_description) {
            //求のformat
            $monster_requests_without_null = $this->format_monster_request_post($request->monster_requests);
            //求のvalidation
            if (!empty($monster_requests_without_null)) {
                foreach ($monster_requests_without_null as $monster_request_key => $monster_request) {
                    $validation_rules = [
                        'name' => 'required',
                        'amount' => 'required'
                    ];
                    $validation_message = [
                        'name.required' => '求のモンスター名が未入力です',
                        'amount.required' => '求の個数が未入力です'
                    ];
                    $validator = Validator::make($monster_request, $validation_rules, $validation_message);
                    if ($validator->fails()) {
                        return redirect($previous_url)->withErrors($validator)->withInput();
                    }
                }
            }
            //出のformat
            $monster_gives_without_null = $this->format_monster_give_post($request->monster_gives);
            //出のvalidation
            if (!empty($monster_gives_without_null)) {
                foreach ($monster_gives_without_null as $monster_give) {
                    $validation_rules = [
                        'name' => 'required',
                        'amount' => 'required'
                    ];
                    $validation_message = [
                        'name.required' => '出のモンスター名が未入力です',
                        'amount.required' => '出の個数が未入力です'
                    ];
                    $validator = Validator::make($monster_give, $validation_rules, $validation_message);
                    if ($validator->fails()) {
                        return redirect($previous_url)->withErrors($validator)->withInput();
                    }
                }
            }
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
        } else {
            //返信
            //交渉の場合validation
            $post_is_not_only_description = $this->post_is_not_only_description($request);
            if ($post_is_not_only_description) {
                //求のformat
                $monster_requests_without_null = $this->format_monster_request_post($request->monster_requests);
                //求のvalidation
                if (!empty($monster_requests_without_null)) {
                    foreach ($monster_requests_without_null as $monster_request_key => $monster_request) {
                        $validation_rules = [
                            'name' => 'required',
                            'amount' => 'required'
                        ];
                        $validation_message = [
                            'name.required' => '求のモンスター名が未入力です',
                            'amount.required' => '求の個数が未入力です'
                        ];
                        $validator = Validator::make($monster_request, $validation_rules, $validation_message);
                        if ($validator->fails()) {
                            return redirect($previous_url)->withErrors($validator)->withInput();
                        }
                    }
                }
                //出のformat
                $monster_gives_without_null = $this->format_monster_give_post($request->monster_gives);
                //出のvalidation
                if (!empty($monster_gives_without_null)) {
                    foreach ($monster_gives_without_null as $monster_give_key => $monster_give) {
                        $validation_rules = [
                            'name' => 'required',
                            'amount' => 'required'
                        ];
                        $validation_message = [
                            'name.required' => '出のモンスター名が未入力です',
                            'amount.required' => '出の個数が未入力です'
                        ];
                        $validator = Validator::make($monster_give, $validation_rules, $validation_message);
                        if ($validator->fails()) {
                            return redirect($previous_url)->withErrors($validator)->withInput();
                        }
                    }
                }
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
        }
        $this->delete_entered_data_from_session();
        session()->forget('modal_is_open');
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
        if (session()->has('monster_requests')) {
            $old_monster_requests = $this->format_monster_request_post(session()->get('monster_requests'));
            if (empty($old_monster_requests)) {
                //form送信時空っぽだとinputが消えてしまうため最低空のinputが一個あるようにする
                $old_monster_requests = [
                    ['name' => '', 'amount' => '']
                ];
            }
        } else {
            $old_monster_requests = [
                ['name' => '', 'amount' => '']
            ];
        }
        if (session()->has('monster_gives')) {
            $old_monster_gives = $this->format_monster_give_post(session()->get('monster_gives'));
            //form送信時空っぽだとinputが消えてしまうため最低空のinputが一個あるようにする
            if (empty($old_monster_gives)) {
                $old_monster_gives = [
                    ['name' => '', 'amount' => '']
                ];
            }
        } else {
            $old_monster_gives = [
                ['name' => '', 'amount' => '']
            ];
        }
        $post = TradeBoardPost::with('trade_post_gives')->with('trade_post_requests')->with('user')->find($id);
        $replies = TradeBoardPost::replies_for_post($id)->with('trade_post_gives')->with('trade_post_requests')->with('user')->get();
        return view('/trade_board/trade_board_thread', compact('post', 'replies', 'old_monster_requests', 'old_monster_gives'));
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
     * format monster requests and return it
     * 
     */
    public function format_monster_request_post($monster_requests)
    {
        $monster_requests_without_null = [];
        foreach ($monster_requests as $monster_request) {
            //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
            $name_and_amount_are_null = empty($monster_request['name']) && empty($monster_request['amount']);
            //片方のみ入力が一個でもある時点でformに戻る
            if (!$name_and_amount_are_null) {
                array_push($monster_requests_without_null, $monster_request);
            }
        }
        return $monster_requests_without_null;
    }
    /**
     * format monster gives and return it
     * 
     */
    public function format_monster_give_post($monster_gives)
    {
        $monster_gives_without_null = [];
        foreach ($monster_gives as $monster_give) {
            //名前と数片方入力済み判定＝＞何か書いていた途中であることがわかる
            $name_and_amount_are_null = empty($monster_give['name']) && empty($monster_give['amount']);
            //片方のみ入力が一個でもある時点でformに戻る
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
    public function post_is_not_only_description($request)
    {
        $monster_requests = $request->monster_requests;
        $monster_gives = $request->monster_gives;
        foreach ($monster_requests as $key => $monster_request) {
            if ($monster_request['name'] == null && $monster_request['amount'] == null) {
                unset($monster_requests[$key]);
            }
        }
        foreach ($monster_gives as $key => $monster_give) {
            if ($monster_give['name'] == null && $monster_give['amount'] == null) {
                unset($monster_gives[$key]);
            }
        }
        if (count($monster_requests) == 0 && count($monster_gives) == 0) {
            return false;
        } else {
            return true;
        }
    }
}
