<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caller_id');
            $table->unsignedBigInteger('receiver_id');
            $table->enum('type', ['voice', 'video']);
            $table->enum('status', ['missed', 'ended', 'ongoing']);
            $table->integer('duration')->nullable(); // duration in seconds
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('caller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
