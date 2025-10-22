<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('to_user_id'); // User who receives the notification
            $table->unsignedBigInteger('from_user_id'); // User who triggered the notification
            $table->string('type'); // like, comment, follow, share
            $table->text('message');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['to_user_id', 'read']);
            $table->index(['to_user_id', 'type', 'read']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};