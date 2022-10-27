<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
        User::create([
            'name' => '村長',
            'email' => 'pazumura.chief@gmail.com',
            'password' => Hash::make('pad-village-yuta-ryudai'), // password
            'pad_id'=>321952291,
            'is_admin'=>1,
            'all_notifications_are_on'=>1,
        ]);
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
