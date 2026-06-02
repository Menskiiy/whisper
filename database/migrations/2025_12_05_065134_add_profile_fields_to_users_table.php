<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->default('default-avatar.png'); // потом будем хранить путь
            $table->string('status')->nullable();        // строка под именем (например: "кодирую на php")
            $table->text('bio')->nullable();             // описание профиля
            $table->date('birthday')->nullable();        // день рождения
            $table->string('location')->nullable();      // город (по желанию)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'status', 'bio', 'birthday', 'location']);
        });
    }
};
