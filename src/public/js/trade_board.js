//求増やすボタン押すと入力欄増える
document.getElementById('increase_monster_requests').addEventListener('click', function() {
    let next_request_box_id_only_number;
    if (document.getElementById('container_for_monster_requests').childElementCount === 0) {
        next_request_box_id_only_number = 0;
    } else {
        next_request_box_id_only_number = Number(document.getElementById('container_for_monster_requests')
            .children[document.getElementById('container_for_monster_requests').childElementCount - 1]
            .id.replace('request_box', ''));
    }
    document.getElementById('container_for_monster_requests').insertAdjacentHTML('beforeend', `<div style="display:flex;" id="request_box${next_request_box_id_only_number+1}">
<div style="display:flex;">
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
            <div style="display:flex;">
        <label style="display:block;">名前：<input class="monster_gives_name" id="monster_gives_name${next_give_box_id_only_number+1}" type="text" name="monster_gives[${next_give_box_id_only_number+1}][name]"></label>
        <label style="display:block;">個数：<input class="monster_gives_amount" id="monster_gives_amount${next_give_box_id_only_number+1}" type="number" name="monster_gives[${next_give_box_id_only_number+1}][amount]"></label>
        </div>
        <div class="delete_give_box" onclick="delete_give_box(${next_give_box_id_only_number+1})">消す</div>
        </div>`);
});

document.getElementById('open_post_trade_form').addEventListener('click', function() {
    document.getElementById('title').classList.add('hidden');
    document.getElementById('post_trade_form_section').classList.remove('hidden');
    document.getElementById('open_post_trade_form').classList.add('hidden');
    document.querySelector('body').style.overflow = 'hidden';
});
document.getElementById('close_form').addEventListener('click', function() {
    document.getElementById('title').classList.remove('hidden');
    document.getElementById('post_trade_form_section').classList.add('hidden');
    document.getElementById('open_post_trade_form').classList.remove('hidden');
    document.querySelector('body').style.overflow = 'auto';
});