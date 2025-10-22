<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_user_columns_to_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('notifications', 'to_user_id')) {
                $table->unsignedBigInteger('to_user_id')->after('id');
            }
            
            if (!Schema::hasColumn('notifications', 'from_user_id')) {
                $table->unsignedBigInteger('from_user_id')->after('to_user_id');
            }
            
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type'); // like, comment, follow, share
            }
            
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message');
            }
            
            if (!Schema::hasColumn('notifications', 'video_id')) {
                $table->unsignedBigInteger('video_id')->nullable();
            }
            
            if (!Schema::hasColumn('notifications', 'comment_id')) {
                $table->unsignedBigInteger('comment_id')->nullable();
            }
            
            if (!Schema::hasColumn('notifications', 'read')) {
                $table->boolean('read')->default(false);
            }

            // Add foreign key constraints
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['to_user_id']);
            $table->dropForeign(['from_user_id']);
            $table->dropForeign(['video_id']);
            $table->dropForeign(['comment_id']);
            
            $table->dropColumn(['to_user_id', 'from_user_id', 'type', 'message', 'video_id', 'comment_id', 'read']);
        });
    }
};