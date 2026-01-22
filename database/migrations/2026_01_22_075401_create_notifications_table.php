<?php
// database/migrations/xxxx_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user receiving notification
    $table->string('type'); // 'group' or 'private' or 'other'
    $table->unsignedBigInteger('message_id')->nullable();
    $table->string('title');
    $table->text('body');
    $table->string('url')->nullable();
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});
