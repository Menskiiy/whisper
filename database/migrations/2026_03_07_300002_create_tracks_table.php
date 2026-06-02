<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->string('album')->nullable();
            $table->string('genre')->nullable();
            $table->string('file');          // audio file path
            $table->string('cover')->nullable(); // cover art
            $table->integer('duration')->default(0); // seconds
            $table->integer('plays_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
        Schema::create('track_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['track_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('track_likes');
        Schema::dropIfExists('tracks');
    }
};
