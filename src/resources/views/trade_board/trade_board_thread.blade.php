@php
$post = (object) [
    'id' => 1,
    'user_id' => 1,
    'description' => null,
    'parent_trade_board_post_id' => null,
    'updated_at' => null,
    'created_at' => '00:00',
    'user_name' => 'yuta',
    'reply_count' => 1,
    'pad_id' => 123456789,
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
];
$replies = collect([
    (object) [
        'id' => 2,
        'user_id' => 1,
        'description' => 'このレートなら出せます',
        'parent_trade_board_post_id' => 1,
        'updated_at' => null,
        'created_at' => '01:00',
        'user_name' => 'yuta',
        'reply_count' => 1,
        'pad_id' => 123456789,
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
        'id' => 3,
        'user_id' => 1,
        'description' => 'ウソップは出せますか？',
        'parent_trade_board_post_id' => 1,
        'updated_at' => null,
        'created_at' => '02:00',
        'user_name' => 'yuta',
        'reply_count' => 2,
        'pad_id' => null,
        'trade_post_requests' => null,
        'trade_post_gives' => null,
    ],
    (object) [
        'id' => 4,
        'user_id' => 1,
        'description' => null,
        'parent_trade_board_post_id' => 1,
        'updated_at' => null,
        'created_at' => '03:00',
        'user_name' => 'yuta',
        'reply_count' => 1,
        'pad_id' => 123456777,
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
]);
@endphp
@extends('layouts.user_page')
@section('content')
    <section style="background-color:#EEF6FF;margin-bottom:50px;margin-top:50px;">
        <div style="margin:10px;padding:10px;background-color:white;border-radius:10px;">
            <div class="flex">
                <div style="width:80%;">{{ $post->user_name }}</div>
                <div style="width:20%;">{{ $post->created_at }}</div>
                {{-- 日付の表示はあとあと時：分に変える https://qiita.com/shimotaroo/items/acd22877a09fb13827fb --}}
            </div>
            @if (!empty($post->pad_id))
                <div>フレンド:{{ $post->pad_id }}</div>
            @endif
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
            @if(!empty($post->description))
            <div>{{ $post->description }}</div>
            @endif
        </div>
        @foreach ($replies as $reply)
            <div style="margin:10px;padding:10px;background-color:white;border-radius:10px;">
                <div class="flex">
                    <div style="width:80%;">{{ $reply->user_name }}</div>
                    <div style="width:20%;">{{ $reply->created_at }}</div>
                    {{-- 日付の表示はあとあと時：分に変える https://qiita.com/shimotaroo/items/acd22877a09fb13827fb --}}
                </div>
                @if (!empty($reply->pad_id))
                    <div>フレンド:{{ $reply->pad_id }}</div>
                @endif
                @if (!empty($reply->trade_post_gives) || !empty($reply->trade_post_requests))
                    <div class="flex">
                        <div>出:</div>
                        <div>
                            @if (!empty($reply->trade_post_gives))
                                @foreach ($reply->trade_post_gives as $give)
                                    {{ $give->monster_name . '×' . $give->monster_amount }}<br>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="flex">
                        <div>求:</div>
                        <div>
                            @if (!empty($reply->trade_post_requests))
                                @foreach ($reply->trade_post_requests as $request)
                                    {{ $request->monster_name . '×' . $request->monster_amount }}<br>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
                @if(!empty($reply->description))
                <div>{{ $reply->description }}</div>
                @endif
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
