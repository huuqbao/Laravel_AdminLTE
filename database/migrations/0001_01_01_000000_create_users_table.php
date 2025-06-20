<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 30);
            $table->string('last_name', 30);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->tinyInteger('status')->default(0); // 0 = chờ duyệt
            $table->enum('role', ['admin', 'user'])->default('user'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
