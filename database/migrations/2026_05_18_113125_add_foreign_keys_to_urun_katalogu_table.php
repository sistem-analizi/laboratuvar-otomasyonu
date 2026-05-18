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
        Schema::table('urun_katalogu', function (Blueprint $table) {
            $table->foreign(['tip_id'], 'fk_katalog_tip')->references(['tip_id'])->on('urun_tipleri')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['marka_id'], 'fk_urun_marka')->references(['marka_id'])->on('markalar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['alt_kategori_id'], 'urun_katalogu_ibfk_1')->references(['alt_kategori_id'])->on('alt_kategori')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urun_katalogu', function (Blueprint $table) {
            $table->dropForeign('fk_katalog_tip');
            $table->dropForeign('fk_urun_marka');
            $table->dropForeign('urun_katalogu_ibfk_1');
        });
    }
};
