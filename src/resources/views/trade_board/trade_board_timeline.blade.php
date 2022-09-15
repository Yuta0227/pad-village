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
        'pad_bool' => false,
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
    ],
    (object) [
        'id' => 2,
        'user_id' => 1,
        'description' => '交渉あり',
        'parent_trade_board_post_id' => null,
        'updated_at' => null,
        'created_at' => '01:00',
        'pad_bool' => false,
        'depth' => 0,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
        'pad_bool' => false,
        'depth' => 0,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
        'pad_bool' => false,
        'depth' => 0,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
        'pad_bool' => false,
        'depth' => 0,
        'user' => (object) [
            'user_name' => 'yuta',
            'pad_id' => 123456789,
        ],
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
    <div class="h-8">
        <h2 class="h-8 ml-5 font-bold fixed w-full bg-blue-50">タイムライン</h2>
    </div>
    <section class="px-5 flex flex-col gap-5 pb-8">
        @foreach ($posts as $post)
            <x-trade-post-card :post="$post">
                <div class="mt-2">
                    <a class="underline text-blue-500"
                        href="/boards/trade/{{ $post->id }}">
                        スレッドで返信する
                    </a>
                </div>
            </x-trade-post-card>
        @endforeach
    </section>
    <button id="open_post_trade_form" class="fixed bottom-10 right-8 bg-blue-500 rounded-full p-4 shadow-md drop-shadow-md">
        <img src="{{ asset('img/plus.svg') }}" alt="plus" width="28">
    </button>
    <form id="" hidden action="/boards/trade/create">
    </form>
@endsection
