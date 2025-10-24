<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_user_columns_to_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'to_user_id')) {
                $table->unsignedBigInteger('to_user_id')->after('id');
            }
            
            if (!Schema::hasColumn('notifications', 'from_user_id')) {
                $table->unsignedBigInteger('from_user_id')->after('to_user_id');
            }
            
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type');
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
        });

        // Add foreign keys **safely**
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                                   FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                                   WHERE TABLE_SCHEMA = DATABASE() 
                                   AND TABLE_NAME = 'notifications' 
                                   AND CONSTRAINT_TYPE = 'FOREIGN KEY'");
        $existing = array_map(fn($fk) => $fk->CONSTRAINT_NAME, $foreignKeys);

        Schema::table('notifications', function (Blueprint $table) use ($existing) {
            if (!in_array('notifications_to_user_id_foreign', $existing)) {
                $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!in_array('notifications_from_user_id_foreign', $existing)) {
                $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!in_array('notifications_video_id_foreign', $existing)) {
                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            }
            if (!in_array('notifications_comment_id_foreign', $existing)) {
                $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            }
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
