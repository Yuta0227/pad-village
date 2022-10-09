{{-- コントローラーで何件の返信を数値でプロパティとして各postに追加する --}}
{{-- parent_chat_post_idがnullのやつだけ取得 --}}
{{-- DBアクセス回数減らすためにparent_chat_post_idがnullじゃないものをparent_chat_post_idでgroupbyしてそれぞれcountで該当postにプロパティ追加 --}}
{{-- userの名前も取得してプロパティに追加 --}}
{{-- created_atはbladeの方で時：分に変える --}}
@extends('layouts.user_page')
@section('content')
    <div class="h-8" id="title">
        <h2 class="h-8 ml-5 font-bold text-sm fixed w-full bg-blue-50">タイムライン</h2>
    </div>
    <section id="post_trade_form_section"
        class="
        @if (!session()->has('modal_is_open')) {{ 'hidden ' }} @endif
        bg-blue-50 w-full z-50 h-screen overflow-y-scroll fixed">
        <button id="close_form">
            <img src="{{ asset('img/close_modal.svg') }}" alt="cross" width="28">
        </button>
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
