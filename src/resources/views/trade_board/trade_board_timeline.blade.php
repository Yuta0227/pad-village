{{-- コントローラーで何件の返信を数値でプロパティとして各postに追加する --}}
{{-- parent_chat_post_idがnullのやつだけ取得 --}}
{{-- DBアクセス回数減らすためにparent_chat_post_idがnullじゃないものをparent_chat_post_idでgroupbyしてそれぞれcountで該当postにプロパティ追加 --}}
{{-- userの名前も取得してプロパティに追加 --}}
{{-- created_atはbladeの方で時：分に変える --}}
@extends('layouts.user_page')
@section('content')
    <div class="h-8" id="title_timeline">
        <h2 class="h-8 ml-5 font-bold fixed w-full bg-blue-50">タイムライン</h2>
    </div>
    <section id="post_trade_form_section"
        style="z-index:100;height:100vh;overflow-y:scroll;position:fixed;overscroll-behavior-y:none;"
        class="hidden bg-blue-50 w-full">
        <button id="close_form">
            <img src="{{ asset('img/close_modal.svg') }}" alt="cross" width="28">
        </button>
        <x-trade-post-form>
            <input hidden value="" name="parent_trade_board_post_id">
            <input hidden value="0" name="depth">
        </x-trade-post-form>
    </section>
    <section id="all_posts" class="px-5 flex flex-col gap-5 pb-8">
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

    <script>
        //求増やすボタン押すと入力欄増える
        document.getElementById('increase_monster_requests').addEventListener('click', function() {
            console.log(document.getElementById('container_for_monster_requests').childElementCount);
            let next_request_box_id_only_number;
            if (document.getElementById('container_for_monster_requests').childElementCount === 0) {
                next_request_box_id_only_number = 0;
            } else {
                next_request_box_id_only_number = Number(document.getElementById('container_for_monster_requests')
                    .children[document.getElementById('container_for_monster_requests').childElementCount - 1]
                    .id.replace('request_box', ''));
            }
            document.getElementById('container_for_monster_requests').insertAdjacentHTML('beforeend', `<div style="display:flex;" id="request_box${next_request_box_id_only_number+1}">
<div>
                <label style="display:block;">名前：<input class="monster_requests_name" id="monster_requests_name${next_request_box_id_only_number+1}" type="text" name="monster_requests[${next_request_box_id_only_number+1}][name]"></label>
                <label style="display:block;">個数：<input class="monster_requests_amount" id="monster_requests_amount${next_request_box_id_only_number+1}" type="number" name="monster_requests[${next_request_box_id_only_number+1}][amount]"></label>
                </div>                            <div id="delete${next_request_box_id_only_number+1}" class="delete_request_box" onclick="delete_request_box(${next_request_box_id_only_number+1})">消す</div></div>
`);
        });

        function delete_request_box(id) {
            document.getElementById(`request_box${id}`).remove();
        }

        function delete_give_box(id) {
            document.getElementById(`give_box${id}`).remove();
        }
        //出増やすボタン押すと入力欄増える
        document.getElementById('increase_monster_gives').addEventListener('click', function() {
            let next_give_box_id_only_number;
            if (document.getElementById('container_for_monster_gives').childElementCount === 0) {
                next_give_box_id_only_number = 0;
            } else {
                next_give_box_id_only_number = Number(document.getElementById('container_for_monster_gives')
                    .children[document.getElementById('container_for_monster_gives').childElementCount - 1].id
                    .replace('give_box', ''));
            }
            document.getElementById('container_for_monster_gives').insertAdjacentHTML('beforeend',
                `<div style="display:flex;" id="give_box${next_give_box_id_only_number+1}">
                    <div>
                <label style="display:block;">名前：<input class="monster_gives_name" id="monster_gives_name${next_give_box_id_only_number+1}" type="text" name="monster_gives[${next_give_box_id_only_number+1}][name]"></label>
                <label style="display:block;">個数：<input class="monster_gives_amount" id="monster_gives_amount${next_give_box_id_only_number+1}" type="number" name="monster_gives[${next_give_box_id_only_number+1}][amount]"></label>
                </div>
                <div class="delete_give_box" onclick="delete_give_box(${next_give_box_id_only_number+1})">消す</div>
                </div>`);
        });

        function disableScroll(event) {
            event.preventDefault();
        }
        document.getElementById('open_post_trade_form').addEventListener('click', function() {
            document.getElementById('title_timeline').classList.add('hidden');
            document.getElementById('post_trade_form_section').classList.remove('hidden');
            document.getElementById('open_post_trade_form').classList.add('hidden');
            document.getElementById('all_posts').style.overflow = 'hidden';
        });
        document.getElementById('close_form').addEventListener('click', function() {
            document.getElementById('title_timeline').classList.remove('hidden');
            document.getElementById('post_trade_form_section').classList.add('hidden');
            document.getElementById('open_post_trade_form').classList.remove('hidden');
            document.getElementById('all_posts').style.overflow = 'auto';
        });
        document.addEventListener('scroll', function() {
            //     if(document.getElementById('all_posts').style.overflow=='hidden'){
            //         console.log('ok');
            //         document.addEventListener('touchmove',disableScroll,{passive:false})
            //     }

            if (document.getElementById('all_posts').style.overflow == 'hidden') {
                document.addEventListener('touchmove', disableScroll, {
                    passive: false
                });
                document.addEventListener('mousewheel', disableScroll, {
                    passive: false
                });
                // document.getElementById('all_posts').style.overflowY='hidden';
            }
        });
    </script>
@endsection
