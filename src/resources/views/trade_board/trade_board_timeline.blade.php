@extends('layouts.user_page')
@section('content')
    <div class="h-8" id="title">
        <h2 class="h-8 ml-5 font-bold text-sm fixed w-full bg-blue-50">タイムライン</h2>
    </div>
    <section id="post_trade_form_section"
        class="
        @if (!session()->has('modal_is_open')) {{ 'hidden ' }} @endif
        bg-blue-50 w-full z-50 h-screen overflow-y-scroll fixed top-0 px-5 pb-5">
        <div class="flex items-center mt-4 mb-6">
            <button id="close_form">
                <img src="{{ asset('img/close_modal.svg') }}" alt="cross" width="28">
            </button>
            @if (!Auth::check())
                <div class="ml-auto">
                    <a href="{{ route('login') }}" class="bg-blue-500 flex gap-1 px-2 rounded-lg ml-auto">
                        <img src="{{ asset('img/enter_village.svg') }}" width="20">
                        <span class="text-white font-bold text-xs leading-8">村に入る</span>
                    </a>
                </div>
            @endif
        </div>
        <x-trade-post-form :old_monster_requests="$old_monster_requests" :old_monster_gives="$old_monster_gives">
            <input hidden value="" name="parent_trade_board_post_id">
            <input hidden value="0" name="depth">
        </x-trade-post-form>
    </section>
    <section id="all_posts" class="px-5 flex flex-col gap-5 pb-8">
        @foreach ($posts as $post)
            <x-trade-post-card :post="$post">
                <div class="mt-2">
                    <a class="underline text-blue-500 text-sm" href="/boards/trade/{{ $post->id }}">
                        スレッドで返信する
                    </a>
                </div>
            </x-trade-post-card>
        @endforeach
    </section>
    <button id="open_post_trade_form" class="fixed bottom-10 right-8 bg-blue-500 rounded-full p-4 shadow-md drop-shadow-md">
        <img src="{{ asset('img/plus.svg') }}" alt="plus" width="28">
    </button>
    <script src="{{ asset('js/trade_board.js') }}"></script>
@endsection
