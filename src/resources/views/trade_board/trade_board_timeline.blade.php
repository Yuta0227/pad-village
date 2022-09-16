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
        @foreach ($posts as $post)
            <x-trade-post-card :post="$post">
                <div class="mt-2">
                    <a class="underline text-blue-500" href="/boards/trade/{{ $post->id }}">
                        スレッドで返信する
                    </a>
                </div>
            </x-trade-post-card>
        @endforeach
    </section>
    <button id="open_post_trade_form" class="fixed bottom-10 right-8 bg-blue-500 rounded-full p-4 shadow-md drop-shadow-md">
        <img src="{{ asset('img/plus.svg') }}" alt="plus" width="28">
    </button>
    <form id="" action="/boards/trade" method="POST">
        @csrf
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p style="color:red;">{{ $error }}</p>
            @endforeach
        @endif
        <input hidden value="" name="parent_trade_board_post_id">
        <input hidden value="0" name="depth">
        <input hidden value="0" name="is_only_description">
        <div>
            <div>求</div>
            <div id="container_for_monster_requests" style="gap:10px;display:flex;flex-direction:column;">
                @if (null !== old('monster_requests'))
                    @foreach (old('monster_requests') as $old_monster_request)
                        <div style="display:flex;" id="request_box{{ $loop->index }}">
                            <div>
                                <label style="display:block;">名前：<input class="monster_requests_name"
                                        id="monster_requests_name{{ $loop->index }}" type="text"
                                        name="monster_requests[{{ $loop->index }}][name]"
                                        value="{{ $old_monster_request['name'] }}" required></label>
                                <label style="display:block;">個数：<input class="monster_requests_amount"
                                        id="monster_requests_amount{{ $loop->index }}" type="number"
                                        name="monster_requests[{{ $loop->index }}][amount]"
                                        value="{{ $old_monster_request['amount'] }}"></label>
                            </div>
                            <div id="delete{{ $loop->index }}" onclick="delete_request_box({{ $loop->index }})">消す</div>
                        </div>
                    @endforeach
                @else
                    <div style="display:flex;" id="request_box0">
                        <div>
                            <label style="display:block;">名前：<input class="monster_requests_name"
                                    id="monster_requests_name0" type="text" name="monster_requests[0][name]"></label>
                            <label style="display:block;">個数：<input class="monster_requests_amount"
                                    id="monster_requests_amount0" type="number" name="monster_requests[0][amount]"></label>
                        </div>
                        <div id="delete0" onclick="delete_request_box(0)">消す</div>
                    </div>
                @endif
            </div>
            <div id="increase_monster_requests">求を増やす</div>
        </div>
        <div>
            <div>出</div>
            <div id="container_for_monster_gives" style="gap:10px;display:flex;flex-direction:column;">
                @if (null !== old('monster_gives'))
                    @foreach (old('monster_gives') as $old_monster_give)
                        <div>
                            <label style="display:block;">名前：<input class="monster_gives_name"
                                    id="monster_gives_name{{ $loop->index }}" type="text"
                                    name="monster_gives[{{ $loop->index }}][name]"
                                    value="{{ $old_monster_give['name'] }}"></label>
                            <label style="display:block;">個数：<input class="monster_gives_amount"
                                    id="monster_gives_amount{{ $loop->index }}" type="number"
                                    name="monster_gives[{{ $loop->index }}][amount]"
                                    value="{{ $old_monster_give['amount'] }}"></label>
                        </div>
                    @endforeach
                @else
                    <div>
                        <label style="display:block;">名前：<input class="monster_gives_name" id="monster_gives_name0"
                                type="text" name="monster_gives[0][name]"></label>
                        <label style="display:block;">個数：<input class="monster_gives_amount" id="monster_gives_amount0"
                                type="number" name="monster_gives[0][amount]"></label>
                    </div>
                @endif
            </div>
            <div id="increase_monster_gives">出を増やす</div>
        </div>
        <textarea name="description" rows="8">{{ old('description') }}</textarea>
        <label>
            @if (old('allow_show_pad_id_bool') === 'on')
                <input type="checkbox" name="allow_show_pad_id_bool" checked>
            @else
                <input type="checkbox" name="allow_show_pad_id_bool">
            @endif
            フレンドID公開する
        </label>
        <input type="submit" value="送信">
    </form>
    <script>
        //求増やすボタン押すと入力欄増える
        document.getElementById('increase_monster_requests').addEventListener('click', function() {
            let current_monster_requests_input_amount = document.getElementById('container_for_monster_requests').childElementCount;
            // if()
            let last_request_box_id=document.getElementById('container_for_monster_requests').children[current_monster_requests_input_amount-1].id;
            let last_request_box_id_only_number=last_request_box_id.replace('request_box','');
            console.log(last_request_box_id_only_number);
            document.getElementById('container_for_monster_requests').insertAdjacentHTML('beforeend', `<div style="display:flex;" id="request_box${last_request_box_id+1}">
<div>
                <label style="display:block;">名前：<input class="monster_requests_name" id="monster_requests_name${last_request_box_id_only_number+1}" type="text" name="monster_requests[${last_request_box_id_only_number+1}][name]"></label>
                <label style="display:block;">個数：<input class="monster_requests_amount" id="monster_requests_amount${last_request_box_id_only_number+1}" type="number" name="monster_requests[${last_request_box_id_only_number+1}][amount]"></label>
                </div>                            <div id="delete${last_request_box_id_only_number+1}" class="delete_request_box" onclick="delete_request_box(${last_request_box_id_only_number+1})">消す</div></div>
`);
        });
        function delete_request_box(id){
            document.getElementById(`request_box${id}`).remove();
        }
        //出増やすボタン押すと入力欄増える
        document.getElementById('increase_monster_gives').addEventListener('click', function() {
            let current_monster_gives_input_amount = document.getElementById('container_for_monster_gives').childElementCount;
            console.log(current_monster_gives_input_amount);
            document.getElementById('container_for_monster_gives').insertAdjacentHTML('beforeend', `<div style="display:flex;" id="request_box${current_monster_gives_input_amount}"><div>
                <label style="display:block;">名前：<input class="monster_gives_name" id="monster_gives_name${current_monster_gives_input_amount}" type="text" name="monster_gives[${current_monster_gives_input_amount}][name]"></label>
                <label style="display:block;">個数：<input class="monster_gives_amount" id="monster_gives_amount${current_monster_gives_input_amount}" type="number" name="monster_gives[${current_monster_gives_input_amount}][amount]"></label>
                </div><div id="delete${current_monster_gives_input_amount}" class="delete_request_box">消す</div></div>`);
        });
    </script>
@endsection
