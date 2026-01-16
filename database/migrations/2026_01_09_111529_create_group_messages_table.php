<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id(); 

            // The group this message belongs to
            $table->foreignId('group_id')
                  ->constrained('groups') // references 'id' on 'groups'
                  ->onDelete('cascade'); // if a group is deleted, its messages are deleted

            // The user who sent this message
            $table->foreignId('sender_id')
                  ->constrained('users') // references 'id' on 'users'
                  ->onDelete('cascade'); // if a user is deleted, their messages are deleted

            $table->text('body'); // message content
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_messages');
    }
};
