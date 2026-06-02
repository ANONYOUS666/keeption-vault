<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('user_id');
            $table->string('path');                    // storage path of this version
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedInteger('version_number')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->index(['file_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_versions');
    }
};
