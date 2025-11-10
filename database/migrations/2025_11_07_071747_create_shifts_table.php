<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateShiftsTable extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('min_start_time')->nullable();
            $table->time('start_time')->nullable();
            $table->time('max_start_time')->nullable();
            $table->time('min_end_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('max_end_time')->nullable();
            $table->integer('break_time_minutes')->nullable();
            $table->tinyInteger('recurring')->default(0);
            $table->integer('repeat_every')->nullable();
            $table->json('days')->nullable();
            $table->date('end_on')->nullable();
            $table->tinyInteger('indefinite')->default(0);
            $table->string('tag')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
