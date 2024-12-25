<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToScoresTable extends Migration
{
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->nullable();
            $table->foreign('game_id', 'game_fk_10342977')->references('id')->on('games');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10342980')->references('id')->on('users');
        });
    }
}
