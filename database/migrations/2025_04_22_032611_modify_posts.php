<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // 修改 created_at 欄位，設置默認為 CURRENT_TIMESTAMP
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();

            // 修改 updated_at 欄位，設置默認為 CURRENT_TIMESTAMP，並在更新時自動更新
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // 回滾時恢復為可空的 timestamp 欄位（根據原始定義調整）
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }
};
