<?php

use App\Enums\RoleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('address')->nullable(); //Địa chỉ không bắt buộc
            $table->tinyInteger('status')->default(0); //0 = chưa kích hoạt
            $table->string('role', 20)->default(RoleStatus::USER->value); //string
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
