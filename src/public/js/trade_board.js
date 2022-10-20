/**
 * フォーム文字列を生成する
 * @param {number} index 各フォームの番号
 * @param {string} formType "request" | "give"
 * @param {string} monster_name モンスター名
 * @param {string | number} monster_amount モンスターの数
 * @returns {string} HTML文字列
 */
const FormHtml = (index, formType, monster_name = "", monster_amount = "") => {
    return `
    <div id="${formType}_box${index}">
        <div class="flex flex-wrap">
            <input class="input w-[calc(100%-96px)] text-sm placeholder:text-gray-400"
            type="text"
            name="monster_${formType + "s"}[${index}][name]"
            value="${monster_name}" placeholder="モンスター名"
            >
            <span class="flex items-center justify-center w-6">×</span>
            <input class="input w-12 text-center text-sm placeholder:text-gray-400"
            type="number"
            name="monster_${formType + "s"}[${index}][amount]"
            value="${monster_amount}" placeholder="1"
            >
            ${DeleteButton(index, formType)}
        </div>
    </div>
    `;
};

/**
 * 各フォームの削除ボタンを生成する
 * @param {number} index 各フォームの番号
 * @param {string} formType "request" | "give"
 * @returns {string} HTML文字列
 */
const DeleteButton = (index, formType) => {
    return index >= 1
        ? `
        <button type="button" class="w-6" onclick="delete_${formType}_box(${index})">
            <img src="/img/delete_button.svg" class="ml-auto" width="16" />
        </button>
        `
        : `
        <button type="button" class="w-6" onclick="delete_${formType}_box(${index})">
            <img src="/img/disabled_delete_button.svg" class="ml-auto" width="16" />
        </button>
        `;
};

//求増やすボタン押すと入力欄増える
document
    .getElementById("increase_monster_requests")
    .addEventListener("click", function () {
        let next_request_box_id_only_number;
        if (
            document.getElementById("container_for_monster_requests")
                .childElementCount === 0
        ) {
            next_request_box_id_only_number = 0;
        } else {
            next_request_box_id_only_number = Number(
                document
                    .getElementById("container_for_monster_requests")
                    .children[
                        document.getElementById(
                            "container_for_monster_requests"
                        ).childElementCount - 1
                    ].id.replace("request_box", "")
            );
        }
        document
            .getElementById("container_for_monster_requests")
            .insertAdjacentHTML(
                "beforeend",
                FormHtml(next_request_box_id_only_number + 1, "request")
            );
    });

function delete_request_box(id) {
    document.getElementById(`request_box${id}`).remove();
}

function delete_give_box(id) {
    document.getElementById(`give_box${id}`).remove();
}
//出増やすボタン押すと入力欄増える
document
    .getElementById("increase_monster_gives")
    .addEventListener("click", function () {
        let next_give_box_id_only_number;
        if (
            document.getElementById("container_for_monster_gives")
                .childElementCount === 0
        ) {
            next_give_box_id_only_number = 0;
        } else {
            next_give_box_id_only_number = Number(
                document
                    .getElementById("container_for_monster_gives")
                    .children[
                        document.getElementById("container_for_monster_gives")
                            .childElementCount - 1
                    ].id.replace("give_box", "")
            );
        }
        document
            .getElementById("container_for_monster_gives")
            .insertAdjacentHTML(
                "beforeend",
                FormHtml(next_give_box_id_only_number + 1, "give")
            );
    });

document
    .getElementById("open_post_trade_form")
    .addEventListener("click", function () {
        document
            .getElementById("post_trade_form_section")
            .classList.remove("hidden");
        document.getElementById("open_post_trade_form").classList.add("hidden");
        document.querySelector("body").style.overflow = "hidden";
    });
document.getElementById("close_form").addEventListener("click", function () {
    document.getElementById("post_trade_form_section").classList.add("hidden");
    document.getElementById("open_post_trade_form").classList.remove("hidden");
    document.querySelector("body").style.overflow = "auto";
});

/**
 * 投稿フォームが開いている場合、背景がスクロールしないようにする
 */
const disableScrollingIfModalIsOpen = () => {
    const modal = document.getElementById("post_trade_form_section");
    if (!modal.classList.contains("hidden")) {
        document.querySelector("body").style.overflow = "hidden";
    }
};

window.onload = disableScrollingIfModalIsOpen;
