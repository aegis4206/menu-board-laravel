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
            //
            $table->unsignedBigInteger('tab_id')->nullable()->after('id');
            $table->foreign('tab_id')->references('id')->on('tabs')->onDelete('restrict');
            $table->unsignedBigInteger('type_id')->nullable()->after('id');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
            $table->dropForeign(['tab_id']);
            $table->dropColumn('tab_id');
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');

        });
    }
};
