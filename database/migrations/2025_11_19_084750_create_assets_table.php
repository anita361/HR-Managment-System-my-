<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('asset_id')->unique()->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('purchase_from')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('supplier')->nullable();
            $table->string('condition')->nullable();
            $table->integer('warranty_months')->nullable();
            $table->decimal('value', 10, 2)->nullable();

            
            $table->unsignedBigInteger('asset_user_id')->nullable();
            $table->foreign('asset_user_id')->references('id')->on('users')->onDelete('set null');

            $table->text('description')->nullable();
            $table->string('status')->default('Available');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
