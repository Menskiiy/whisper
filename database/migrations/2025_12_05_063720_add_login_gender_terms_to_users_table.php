<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Логин — уникальный, после id
            $table->string('login')->unique()->after('id');

            // Пол — после name (логичнее по структуре профиля)
            $table->enum('gender', ['male', 'female', 'other'])
                  ->nullable()
                  ->after('name');

            // Согласие с правилами — после password
            $table->boolean('terms_accepted')
                  ->default(false)
                  ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login', 'gender', 'terms_accepted']);
        });
    }
};