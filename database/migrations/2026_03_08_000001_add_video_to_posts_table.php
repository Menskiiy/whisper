<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video')->nullable()->after('image');
            $table->string('media_type')->nullable()->after('video'); // 'image', 'gif', 'video'
        });
    }

    public function down(): void {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['video', 'media_type']);
        });
    }
};
