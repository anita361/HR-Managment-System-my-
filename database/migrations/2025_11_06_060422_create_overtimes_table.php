<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('ot_date');
            $table->decimal('ot_hours', 5, 2); 
            $table->string('ot_type')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
