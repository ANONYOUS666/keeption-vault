<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique(); // e.g. 8F4T2R
            $table->unsignedBigInteger('user_id');
            $table->json('file_ids')->nullable();       // JSON array of file IDs
            $table->json('file_meta')->nullable();      // file names/sizes for display
            $table->timestamp('expires_at');
            $table->unsignedInteger('max_uses')->nullable(); // null = unlimited
            $table->unsignedInteger('use_count')->default(0);
            $table->string('password_hash')->nullable();
            $table->boolean('allow_download')->default(true);
            $table->boolean('allow_reshare')->default(false);
            $table->boolean('self_destruct')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('destroyed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['code', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_codes');
    }
};
