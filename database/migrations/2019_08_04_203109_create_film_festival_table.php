<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFilmFestivalTable extends Migration
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
            Schema::create('film_festival', function (Blueprint $table) {
                $table->increments('id');
                $table->text('name');
                $table->integer('edition');
                $table->timestamps();
            });

            Schema::table('movie', function (Blueprint $table) {
                $table->integer('film_festival_id');
                /** @noinspection PhpUndefinedMethodInspection */
                $table->foreign('film_festival_id')
                    ->references('id')
                    ->on('film_festival');
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
        Schema::dropIfExists('film_festival');
        Schema::table('movie', function (Blueprint $table) {
            $table->dropForeign('film_festival_id');
            $table->dropColumn('film_festival_id');
        });
    }
}
