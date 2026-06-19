<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->text('short_description');
            $table->longText('content');
            $table->string('category');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};