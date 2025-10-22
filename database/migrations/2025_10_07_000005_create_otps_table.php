<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_otps_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp_code', 6);
            $table->string('token');
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();

            $table->index(['email', 'used']);
            $table->index('token');
        });
    }

    public function down()
    {
        Schema::dropIfExists('otps');
    }
};