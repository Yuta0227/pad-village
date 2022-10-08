<form action="/boards/trade" method="POST">
    @csrf
    <div style="text-align: center;">投稿フォーム</div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
    @endif
    {{ $slot }}
    <div>
        <div style="text-align: center;">求</div>
        <div id="container_for_monster_requests" style="gap:10px;display:flex;flex-direction:column;">
            @if (session()->has('monster_requests'))
                @foreach (session()->get('monster_requests') as $old_monster_request)
                    <div style="display:flex;" id="request_box{{ $loop->index }}">
                        <div style="display:flex;">
                            <label style="display:block;">名前：<input class="monster_requests_name"
                                    id="monster_requests_name{{ $loop->index }}" type="text"
                                    name="monster_requests[{{ $loop->index }}][name]"
                                    value="{{ $old_monster_request['name'] }}"></label>
                            <label style="display:block;">個数：<input class="monster_requests_amount"
                                    id="monster_requests_amount{{ $loop->index }}" type="number"
                                    name="monster_requests[{{ $loop->index }}][amount]"
                                    value="{{ $old_monster_request['amount'] }}"></label>
                        </div>
                        <button type="button" onclick="delete_request_box({{ $loop->index }})">消す</button>
                    </div>
                @endforeach
            @else
                <div style="display:flex;" id="request_box0">
                    <div>
                        <label style="display:block;">名前：<input class="monster_requests_name"
                                id="monster_requests_name0" type="text" name="monster_requests[0][name]"></label>
                        <label style="display:block;">個数：<input class="monster_requests_amount"
                                id="monster_requests_amount0" type="number"
                                name="monster_requests[0][amount]"></label>
                    </div>
                    <button type="button" onclick="delete_request_box(0)">消す</button>
                </div>
            @endif
        </div>
        <div id="increase_monster_requests">求を増やす</div>
    </div>
    <div>
        <div style="text-align: center;">出</div>
        <div id="container_for_monster_gives" style="gap:10px;display:flex;flex-direction:column;">
            @if (session()->has('monster_gives'))
                @foreach (session()->get('monster_gives') as $old_monster_give)
                    <div style="display:flex;" id="give_box{{ $loop->index }}">
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
                        <div onclick="delete_give_box({{ $loop->index }})">消す</div>
                    </div>
                @endforeach
            @else
                <div style="display:flex;" id="give_box0">
                    <div>
                        <label style="display:block;">名前：<input class="monster_gives_name" id="monster_gives_name0"
                                type="text" name="monster_gives[0][name]"></label>
                        <label style="display:block;">個数：<input class="monster_gives_amount"
                                id="monster_gives_amount0" type="number" name="monster_gives[0][amount]"></label>
                    </div>
                    <div onclick="delete_give_box(0)">消す</div>
                </div>
            @endif
        </div>
        <div id="increase_monster_gives">出を増やす</div>
    </div>
    <textarea name="description" rows="8">
@if (session()->has('description'))
        {{ session()->get('description') }}
        @endif
</textarea>
    <label>
        <input type="checkbox" name="allow_show_pad_id_bool"
            @if (session()->has('allow_show_pad_id_bool')) @if (session()->get('allow_show_pad_id_bool') === 'on')
        checked @endif
            @endif
        >
        フレンドID公開する
    </label>
    <input type="submit" value="送信">
</form>