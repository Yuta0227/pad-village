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
        'chat_id' => 1,
        'description' => 'ルフィつよすぎ',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '00:00',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 2,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'ゾロつよすぎ',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '01:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 3,
        'user_id' => null,
        'chat_id' => 1,
        'description' => 'レアリティLSだるすぎ',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '02:01',
        'user_name' => '匿名ユーザー',
    ],
    (object) [
        'id' => 4,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'だいけ暴れすぎああああああああああああああああああああ',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '03:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 5,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 6,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 7,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 8,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 9,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 10,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
    ],
    (object) [
        'id' => 11,
        'user_id' => 1,
        'chat_id' => 1,
        'description' => 'シヴァ&ドラゴンズおもんない',
        'parent_chat_post_id' => null,
        'updated_at' => null,
        'created_at' => '04:01',
        'user_name' => 'yuta',
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
                <div>{{ $post->description }}</div>
            </div>
        @endforeach
    </section>
    <form action="{{ route('add_reply', ['chat_id' => $chat_id]) }}" method="POST"
        style="height:50px;bottom:0;padding:10px;background-color:#EEF6FF;gap:1px;" class="flex left-0 w-full fixed">
        @csrf
        @if (Auth::check())
            <input name="user_id" value="{{ Auth::id() }}" hidden>
        @else
            <input name="user_id" value="null" hidden>
        @endif
        <input placeholder="入力してください" name="description" style="font-size:smaller;width:85%;border:1px solid black;border-radius:10px;">
        <input value="返信" type="submit"
            style="font-size:smaller;:bold;color:white;width:15%;text-align:center;border:1px solid black;background-color:#3B81F6;border-radius:10px;">
    </form>
@endsection
