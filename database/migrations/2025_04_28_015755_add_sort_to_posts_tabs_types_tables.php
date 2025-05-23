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
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('sort')->default(0)->after('id'); // after('id')可以指定放在 id 後面
        });

        Schema::table('tabs', function (Blueprint $table) {
            $table->integer('sort')->default(0)->after('id');
        });

        Schema::table('types', function (Blueprint $table) {
            $table->integer('sort')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('tabs', function (Blueprint $table) {
            $table->dropColumn('sort');
        });

        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
