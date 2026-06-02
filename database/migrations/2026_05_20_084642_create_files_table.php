<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');                    // original filename
            $table->string('path');                    // storage path
            $table->string('mime_type')->nullable();
            $table->string('category', 20)->default('other'); // photo/video/music/doc/other
            $table->unsignedBigInteger('size')->default(0);   // bytes
            $table->unsignedBigInteger('folder_id')->nullable(); // future folder support
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_deleted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
