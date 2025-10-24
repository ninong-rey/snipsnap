<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'shares_count')) {
                $table->unsignedBigInteger('shares_count')->default(0)->after('likes_count');
            }
        });
    }

    public function down() {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('shares_count');
        });
    }
};
