<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id'); // The owner of the team (user who purchased the teams subscription)
            $table->unsignedBigInteger('user_id')->nullable(); // Associated registered user id (once they join)
            $table->string('invited_email'); // Email of invited person
            $table->string('name')->nullable();
            $table->string('role')->default('editor'); // admin, editor, viewer
            $table->string('status')->default('pending'); // active, pending
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
