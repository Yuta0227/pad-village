@extends('layouts.user_page')
@section('content')
    <div class="h-8">
        <h2 class="flex ml-5 font-bold fixed w-full bg-blue-50 text-sm">
            <a @if (empty($post->parent_trade_board_post_id)) href="/boards/trade"
            @else
            href="/boards/trade/{{ $post->parent_trade_board_post_id }}" @endif
                class="block mr-2 my-auto"><img src="{{ asset('/img/go_back_arrow.svg') }}" class="-mt-1"></a>
            <span>スレッド</span>
        </h2>
    </div>
    <section class="px-5 pb-8">
        <x-trade-post-card :post="$post" />
        <section class="flex flex-col gap-5 mt-5 pt-5 border-t-2 border-gray-400">
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
    </div>
@endsection
