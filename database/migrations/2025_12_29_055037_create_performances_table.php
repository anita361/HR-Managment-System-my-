<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('department');
            $table->string('designation');
            $table->string('qualification');
            $table->string('emp_id');
            $table->date('date_of_join')->nullable();
            $table->date('date_of_confirmation')->nullable();
            $table->string('previous_experience')->nullable();

            // ✅ Added fields
            $table->string('ro_name')->nullable();
            $table->string('ro_designation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
