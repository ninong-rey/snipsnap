<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('bio')->nullable();
            $table->string('phone')->nullable()->unique(); // ✅ phone is optional but must be unique if filled
            $table->string('avatar')->nullable();
            $table->string('email')->nullable()->unique(); // ✅ email is optional but must be unique if filled
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
