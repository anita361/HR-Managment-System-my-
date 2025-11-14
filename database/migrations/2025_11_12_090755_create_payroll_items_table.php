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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Item name
            $table->string('category'); // e.g., Allowance, Bonus, etc.
            $table->boolean('unit_calculation')->default(0); // Whether unit calculation applies
            $table->decimal('unit_amount', 10, 2)->default(0); // Per-unit amount
            $table->string('assignee'); // 'all', 'individual', etc.
            $table->unsignedBigInteger('employee_id')->nullable(); // If assigned to one employee
            $table->timestamps();

            // Foreign key relation (optional)
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
