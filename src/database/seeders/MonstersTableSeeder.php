<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;


class MonstersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = storage_path() . '/json/monster_information.json';
        $json_string = file_get_contents($url);
        $monster_json = json_decode($json_string, true);
        $data = array();
        foreach ($monster_json as $monster) {
            $new_monster = ['id' => $monster['no'], 'name' => $monster['name']];
            array_push($data, $new_monster);
        }
        DB::table('monsters')->insert($data);
    }
}
