{{-- コントローラーで何件の返信を数値でプロパティとして各postに追加する --}}
{{-- parent_chat_post_idがnullのやつだけ取得 --}}
{{-- DBアクセス回数減らすためにparent_chat_post_idがnullじゃないものをparent_chat_post_idでgroupbyしてそれぞれcountで該当postにプロパティ追加 --}}
{{-- userの名前も取得してプロパティに追加 --}}
{{-- created_atはbladeの方で時：分に変える --}}
@extends('layouts.user_page')
@section('content')
    <div class="h-8">
        <h2 class="h-8 ml-5 font-bold fixed w-full bg-blue-50">タイムライン</h2>
    </div>
    <section class="px-5 flex flex-col gap-5 pb-8">
        @foreach ($posts as $date=>$posts_grouped_by_date)
            <div class="w-full items-center flex" style="justify-content:center;">
                <div class="bg-gray-300" style="width:50%;border-radius:9999px;text-align:center;">{{ $date }}</div>
            </div>
            @foreach ($posts_grouped_by_date as $post)
                <x-trade-post-card :post="$post">
                    <div class="mt-2">
                        <a class="underline text-blue-500" href="/boards/trade/{{ $post->id }}">
                            スレッドで返信する
                        </a>
                    </div>
                </x-trade-post-card>
            @endforeach
        @endforeach
    </section>
    <button id="open_post_trade_form" class="fixed bottom-10 right-8 bg-blue-500 rounded-full p-4 shadow-md drop-shadow-md">
        <img src="{{ asset('img/plus.svg') }}" alt="plus" width="28">
    </button>
    <form id="" hidden action="/boards/trade/create">
    </form>
@endsection
