<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'likes_count')) {
                $table->unsignedBigInteger('likes_count')->default(0)->after('url');
            }
            if (!Schema::hasColumn('videos', 'comments_count')) {
                $table->unsignedBigInteger('comments_count')->default(0)->after('likes_count');
            }
            if (!Schema::hasColumn('videos', 'shares_count')) {
                $table->unsignedBigInteger('shares_count')->default(0)->after('comments_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'likes_count')) {
                $table->dropColumn('likes_count');
            }
            if (Schema::hasColumn('videos', 'comments_count')) {
                $table->dropColumn('comments_count');
            }
            if (Schema::hasColumn('videos', 'shares_count')) {
                $table->dropColumn('shares_count');
            }
        });
    }
};
