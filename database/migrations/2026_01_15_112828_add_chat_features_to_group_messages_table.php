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
        Schema::table('group_messages', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false)->after('body');
            $table->json('deleted_for')->nullable()->after('is_deleted');
            $table->timestamp('edited_at')->nullable()->after('deleted_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_messages', function (Blueprint $table) {
            $table->dropColumn(['is_deleted', 'deleted_for', 'edited_at']);
        });
    }
};
