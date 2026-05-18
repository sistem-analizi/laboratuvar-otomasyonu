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
        Schema::table('tedarik_kaynaklari', function (Blueprint $table) {
            $table->foreign(['tedarik_tip_id'], 'fk_tedarik_tipi')->references(['id'])->on('tedarik_tipleri')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tedarik_kaynaklari', function (Blueprint $table) {
            $table->dropForeign('fk_tedarik_tipi');
        });
    }
};
