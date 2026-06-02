<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_private')->default(false)->after('location');
            $table->string('website')->nullable()->after('is_private');
            $table->string('accent_color')->nullable()->default('#7c5af5')->after('website');
            $table->string('banner')->nullable()->after('accent_color');
            $table->string('vk')->nullable()->after('banner');
            $table->string('telegram')->nullable()->after('vk');
            $table->string('instagram')->nullable()->after('telegram');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_private','website','accent_color','banner','vk','telegram','instagram']);
        });
    }
};
