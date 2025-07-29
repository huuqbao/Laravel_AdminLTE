<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Xóa comment không có user (nếu có)
        DB::table('comments')->whereNull('user_id')->delete();

        // Xóa foreign key hiện tại
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Đổi cột user_id thành NOT NULL
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        // Thêm lại foreign key với ON DELETE CASCADE
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Xóa foreign key mới
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Đổi lại thành nullable
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // Thêm lại foreign key cũ với SET NULL
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};

