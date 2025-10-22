<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'likes_count')) {
                $table->unsignedBigInteger('likes_count')->default(0);
            }
            if (!Schema::hasColumn('videos', 'comments_count')) {
                $table->unsignedBigInteger('comments_count')->default(0);
            }
            if (!Schema::hasColumn('videos', 'shares_count')) {
                $table->unsignedBigInteger('shares_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['likes_count', 'comments_count', 'shares_count']);
        });
    }
};
