<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->unsignedBigInteger('shift_id');
            $table->time('min_start_time');
            $table->time('start_time');
            $table->time('max_start_time');
            $table->time('min_end_time');
            $table->time('end_time');
            $table->time('max_end_time');
            $table->string('break_time');
            $table->boolean('accept_extra_hours')->default(false);
            $table->boolean('publish')->default(false);
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shift_schedules');
    }
}
