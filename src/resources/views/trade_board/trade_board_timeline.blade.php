{{-- コントローラーで何件の返信を数値でプロパティとして各postに追加する --}}
{{-- parent_chat_post_idがnullのやつだけ取得 --}}
{{-- DBアクセス回数減らすためにparent_chat_post_idがnullじゃないものをparent_chat_post_idでgroupbyしてそれぞれcountで該当postにプロパティ追加 --}}
{{-- userの名前も取得してプロパティに追加 --}}
{{-- created_atはbladeの方で時：分に変える --}}
@php
$posts = collect([
    (object) [
        'id' => 1,
        'user_id' => 1,
        'description' => null,
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '00:00',
        'user_name' => 'yuta',
        'reply_count' => 1,
        'trade_post_requests' => (object) [
            (object) [
                'trade_board_post_id' => 1,
                'monster_amount' => 2,
                'monster_id' => 1,
                'monster_name' => 'たまドラ',
            ],
            (object) [
                'trade_board_post_id' => 1,
                'monster_amount' => 4,
                'monster_id' => 2,
                'monster_name' => 'ホノピィ',
            ],
        ],
        'trade_post_gives' => (object) [
            (object) [
                'trade_board_post_id' => 1,
                'monster_amount' => 2,
                'monster_id' => 8,
                'monster_name' => '火ノエル',
            ],
            (object) [
                'trade_board_post_id' => 1,
                'monster_amount' => 4,
                'monster_id' => 3,
                'monster_name' => 'ミズピィ',
            ],
        ],
    ],
    (object) [
        'id' => 2,
        'user_id' => 1,
        'description' => '交渉あり',
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '01:00',
        'user_name' => 'yuta',
        'reply_count' => 0,
        'trade_post_requests' => (object) [
            (object) [
                'trade_board_post_id' => 2,
                'monster_amount' => 2,
                'monster_id' => 1,
                'monster_name' => 'たまドラ',
            ],
        ],
        'trade_post_gives' => (object) [
            (object) [
                'trade_board_post_id' => 2,
                'monster_amount' => 2,
                'monster_id' => 8,
                'monster_name' => '火ノエル',
            ],
        ],
    ],
    (object) [
        'id' => 3,
        'user_id' => 1,
        'description' => '俺様に素材をよこせ',
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '02:00',
        'user_name' => 'yuta',
        'reply_count' => 4,
        'trade_post_requests' => (object) [
            (object) [
                'trade_board_post_id' => 3,
                'monster_amount' => 2,
                'monster_id' => 1,
                'monster_name' => 'たまドラ',
            ],
            (object) [
                'trade_board_post_id' => 3,
                'monster_amount' => 4,
                'monster_id' => 2,
                'monster_name' => 'ホノピィ',
            ],
        ],
        'trade_post_gives' => null,
    ],
    (object) [
        'id' => 4,
        'user_id' => 1,
        'description' => '引退するので配布します',
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '03:00',
        'user_name' => 'yuta',
        'reply_count' => 3,
        'trade_post_requests' => null,
        'trade_post_gives' => (object) [
            (object) [
                'trade_board_post_id' => 4,
                'monster_amount' => 2,
                'monster_id' => 8,
                'monster_name' => '火ノエル',
            ],
            (object) [
                'trade_board_post_id' => 4,
                'monster_amount' => 4,
                'monster_id' => 3,
                'monster_name' => 'ミズピィ',
            ],
        ],
    ],
    (object) [
        'id' => 5,
        'user_id' => 1,
        'description' => '提案待ち',
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:00',
        'user_name' => 'yuta',
        'reply_count' => 0,
        'trade_post_requests' => (object) [
            (object) [
                'trade_board_post_id' => 5,
                'monster_amount' => 2,
                'monster_id' => 1,
                'monster_name' => 'たまドラ',
            ],
            (object) [
                'trade_board_post_id' => 5,
                'monster_amount' => 4,
                'monster_id' => 2,
                'monster_name' => 'ホノピィ',
            ],
        ],
        'trade_post_gives' => null,
    ],
]);
@endphp
@extends('layouts.user_page')
@section('content')
    <section style="background-color:#EEF6FF;margin-bottom:50px;margin-top:50px;">
        @foreach ($posts as $post)
            <div style="margin:10px;padding:10px;background-color:white;border-radius:10px;">
                <div class="flex">
                    <div style="width:80%;">{{ $post->user_name }}</div>
                    <div style="width:20%;">{{ $post->created_at }}</div>
                    {{-- 日付の表示はあとあと時：分に変える https://qiita.com/shimotaroo/items/acd22877a09fb13827fb --}}
                </div>
                <div>フレンド</div>
                <div class="flex">
                    <div>出:</div>
                    <div>
                        @if (!empty($post->trade_post_gives))
                            @foreach ($post->trade_post_gives as $give)
                                {{ $give->monster_name . '×' . $give->monster_amount }}<br>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="flex">
                    <div>求:</div>
                    <div>
                        @if (!empty($post->trade_post_requests))
                            @foreach ($post->trade_post_requests as $request)
                                {{ $request->monster_name . '×' . $request->monster_amount }}<br>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div>備考:{{ $post->description }}</div>
                <div>
                    <a class="underline text-blue-500"
                        href="{{ route('trade_board_thread', ['parent_trade_post_id' => $post->id]) }}">スレッドで返信する</a>
                </div>
            </div>
        @endforeach
    </section>
    <div style="height:50px;bottom:0;padding:10px;background-color:#EEF6FF;gap:1px;" class="flex left-0 w-full fixed">
        <button id="open_post_trade_form"
            style="font-size:smaller;font-weight:bold;color:white;width:100%;text-align:center;border:1px solid black;background-color:#3B81F6;border-radius:10px;">
            投稿する</button>
    </div>
    <form id="" hidden action="{{ route('post_to_trade_board') }}">
    </form>
@endsection
