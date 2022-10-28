<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //データベースに保存されているデータを変数にいれて差分のみ挿入できるようにしないと仮にモンポの情報をaddcolumnしたとしても上書きされるという悪夢
        $url = storage_path() . '/json/monster_data.json';
        $json_string = file_get_contents($url);
        $monster_json = json_decode($json_string, true);
        $data = array();
        foreach ($monster_json as $monster) {
            $new_monster = ['id' => $monster['no'], 'name' => $monster['name']];
            array_push($data, $new_monster);
        }
        DB::table('monsters')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
