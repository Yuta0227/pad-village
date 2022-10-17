@extends('layouts.user_page')
@section('content')
    <div class="h-8" id="title">
        <h2 class="flex ml-5 font-bold text-sm fixed w-full bg-blue-50">
            <a @if (empty($post->parent_trade_board_post_id)) href="/boards/trade"
            @else
            href="/boards/trade/{{ $post->parent_trade_board_post_id }}" @endif
                class="block mr-2 my-auto"><img src="{{ asset('/img/go_back_arrow.svg') }}" class="-mt-1"></a>
            <span>スレッド</span>
        </h2>
    </div>
    <section id="post_trade_form_section"
        class="
        @if (!session()->has('modal_is_open')) {{ 'hidden' }} @endif
        bg-blue-50 w-full z-50 h-screen overflow-y-scroll fixed top-0 px-5 pb-5">
        <button id="close_form" class="mt-4 mb-4">
            <img src="{{ asset('img/close_modal.svg') }}" alt="cross" width="28">
        </button>
        <x-trade-post-form :old_monster_requests="$old_monster_requests" :old_monster_gives="$old_monster_gives">
            <input hidden value="{{ $post->id }}" name="parent_trade_board_post_id">
            <input hidden value="{{ $post->depth + 1 }}" name="depth">
        </x-trade-post-form>
    </section>
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
    </form>
    </div>
    <script src="{{ asset('js/trade_board.js') }}"></script>
@endsection
