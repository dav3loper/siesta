<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use siesta\domain\cinema\Cinema;
use siesta\domain\session\Session;

class CreateTableSchedule extends Migration
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
            Schema::create('schedule', function (Blueprint $table) {
                $table->increments('id');
                $table->enum('cinema', Cinema::THEATER_LIST);
                $table->dateTime('starts_at');
                $table->dateTime('ends_at');
                $table->integer('duration');
                $table->text('extra_info');
                $table->enum('type', Session::TYPE_LIST);
                $table->text('name')->nullable();
                $table->timestamps();
            });

            Schema::table('session', function (Blueprint $table) {
                $table->increments('id');
                $table->foreign('schedule_id')
                    ->references('id')
                    ->on('schedule');
                $table->foreign('movie_id')
                    ->references('id')
                    ->on('movie');
                $table->timestamps();
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
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('session');
    }
}
