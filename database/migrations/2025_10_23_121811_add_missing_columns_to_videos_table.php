<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('caption')->after('description');
            $table->unsignedBigInteger('likes_count')->default(0)->after('user_id');
            $table->unsignedBigInteger('comments_count')->default(0)->after('likes_count');
            $table->unsignedBigInteger('shares_count')->default(0)->after('comments_count');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['caption', 'likes_count', 'comments_count', 'shares_count']);
        });
    }
};