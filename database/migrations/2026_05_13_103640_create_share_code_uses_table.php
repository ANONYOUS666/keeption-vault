<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('share_code_uses')) {
            Schema::create('share_code_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code_id');
            $table->timestamp('used_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser_name', 100)->nullable();
            $table->boolean('downloaded')->default(false);

            $table->foreign('code_id')->references('id')->on('share_codes')->onDelete('cascade');
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('share_code_uses');
    }
};
