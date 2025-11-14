<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollOvertimesTable extends Migration
{
    public function up()
    {
        Schema::create('payrollovertimes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('rate_type', ['Daily Rate', 'Hourly Rate']);
            $table->decimal('rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrollovertimes');
    }
}

