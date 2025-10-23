<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create temporary table with correct structure
        Schema::create('videos_temp', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('caption')->nullable();
            $table->string('url');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);
            $table->timestamps();
        });

        // Copy existing data
        \DB::statement('INSERT INTO videos_temp SELECT * FROM videos');
        
        // Replace the old table
        Schema::dropIfExists('videos');
        Schema::rename('videos_temp', 'videos');
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};