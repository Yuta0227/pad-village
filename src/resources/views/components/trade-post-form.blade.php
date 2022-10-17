@props(['old_monster_requests', 'old_monster_gives'])
<form action="/boards/trade" method="POST">
    @csrf
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="text-red-500 mb-6">{{ $error }}</p>
        @endforeach
    @endif
    {{ $slot }}
    <div>
        <div class="label mb-1">求</div>
        <div id="container_for_monster_requests" class="flex flex-col gap-2">
            @foreach ($old_monster_requests as $old_monster_request)
                <div id="request_box{{ $loop->index }}">
                    <div class="flex flex-wrap">
                        <input class="monster_requests_name input w-[calc(100%-96px)] text-sm placeholder:text-gray-400"
                            id="monster_requests_name{{ $loop->index }}" type="text"
                            name="monster_requests[{{ $loop->index }}][name]"
                            value="{{ $old_monster_request['name'] }}" placeholder="モンスター名">
                        <span class="flex items-center justify-center w-6">×</span>
                        <input class="monster_requests_amount input w-12 text-center text-sm placeholder:text-gray-400"
                            id="monster_requests_amount{{ $loop->index }}" type="number"
                            name="monster_requests[{{ $loop->index }}][amount]"
                            value="{{ $old_monster_request['amount'] }}" placeholder="1">
                        <button type="button" class="w-6"
                            @if (!$loop->first) onclick="delete_request_box({{ $loop->index }})" @endif>
                            <img src="{{ !$loop->first ? asset('img/delete_button.svg') : asset('img/disabled_delete_button.svg') }}"
                                class="ml-auto" width="16" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="increase_monster_requests" class="text-gray-400 text-sm mt-2">+さらに追加</div>
    </div>
    <div class="mt-5">
        <div class="label mb-1">出</div>
        <div id="container_for_monster_gives" class="flex flex-col gap-2">
            @foreach ($old_monster_gives as $old_monster_give)
                <div id="give_box{{ $loop->index }}">
                    <div class="flex flex-wrap">
                        <input class="monster_gives_name input w-[calc(100%-96px)] text-sm placeholder:text-gray-400"
                            id="monster_gives_name{{ $loop->index }}" type="text"
                            name="monster_gives[{{ $loop->index }}][name]" value="{{ $old_monster_give['name'] }}"
                            placeholder="モンスター名">
                        <span class="flex items-center justify-center w-6">×</span>
                        <input class="monster_gives_amount input w-12 text-center text-sm placeholder:text-gray-400"
                            id="monster_gives_amount{{ $loop->index }}" type="number"
                            name="monster_gives[{{ $loop->index }}][amount]"
                            value="{{ $old_monster_give['amount'] }}" placeholder="1">
                        <button type="button" class="w-6"
                            @if (!$loop->first) onclick="delete_request_box({{ $loop->index }})" @endif>
                            <img src="{{ !$loop->first ? asset('img/delete_button.svg') : asset('img/disabled_delete_button.svg') }}"
                                class="ml-auto" width="16" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div id="increase_monster_gives" class="text-gray-400 text-sm mt-2">+さらに追加</div>
    </div>
    <div class="mt-5">
        <label for="description" class="label mb-1">備考</label>
        <textarea id="description" name="description" rows="8" class="input resize-none w-full">{{ session()->has('description') ? session()->get('description') : '' }}</textarea>
    </div>
    <label>
        <input type="checkbox" name="allow_show_pad_id_bool" class="checkbox"
            @if (session()->has('allow_show_pad_id_bool')) @if (session()->get('allow_show_pad_id_bool') === 'on')
        checked @endif @endif
        >
        <span class="ml-1 text-sm text-gray-600">
            フレンドIDを公開する
        </span>
    </label>
    <div class="text-right">
        <x-button class="ml-auto">送信</x-button>
    </div>
</form>
