<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Cho phép null nếu là guest
            $table->string('ip_address')->nullable(); // dùng IP nếu là guest
            $table->morphs('likeable'); // likeable_type + likeable_id
            $table->timestamps();

            $table->unique(['user_id', 'likeable_id', 'likeable_type']); // Nếu là user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
