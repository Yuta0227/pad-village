@php
$post = (object) [
    'id' => 1,
    'user_id' => 1,
    'description' => null,
    'parent_trade_board_post_id' => null,
    'updated_at' => null,
    'created_at' => '00:00',
    'pad_bool' => true,
    'depth' => 0,
    'user' => (object) [
        'user_name' => 'yuta',
        'pad_id' => 123456789,
    ],
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
        'pad_bool' => true,
        'depth' => 1,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
        'pad_bool' => false,
        'depth' => 1,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
        'pad_bool' => true,
        'depth' => 1,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
    <div class="h-8">
        <h2 class="flex ml-5 font-bold fixed w-full bg-blue-50">
            <a @if (empty($post->parent_trade_board_post_id)) href="/boards/trade"
            @else
            href="/boards/trade/{{ $post->parent_trade_board_post_id }}" @endif
                class="block mr-2 my-auto"><img src="{{ asset('/img/go_back_arrow.svg') }}" class="-mt-1"></a>
            <span>スレッド</span>
        </h2>
    </div>
    <section class="px-5 pb-8">
        <x-trade-post-card :post="$post" />
        <section class="flex flex-col gap-5 mt-5 pt-5 border-t-2">
            @foreach ($replies as $reply)
                <x-trade-post-card :post="$reply"></x-trade-post-card>
            @endforeach
        </section>
    </section>
    <button id="open_post_trade_form" class="fixed bottom-10 right-8 bg-blue-500 rounded-full p-4 shadow-md drop-shadow-md">
        {{-- 投稿or返信はモーダル内で選べる --}}
        <img src="{{ asset('img/plus.svg') }}" alt="plus" width="28">
    </button>
    <form id="" hidden action="/boards/trade/create">
    </form>
@endsection
