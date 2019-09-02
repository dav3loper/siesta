<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewUserVoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::beginTransaction();
            Schema::create('user_vote', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('movie_id');
                $table->integer('score');
                $table->timestamps();
            });

            Schema::table('user_vote', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
                $table->foreign('movie_id')
                    ->references('id')
                    ->on('movie');
            });
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_vote');
    }
}
