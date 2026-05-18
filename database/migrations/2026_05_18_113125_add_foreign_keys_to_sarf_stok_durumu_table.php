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
        Schema::table('sarf_stok_durumu', function (Blueprint $table) {
            $table->foreign(['konum_id'], 'fk_sarf_konum')->references(['konum_id'])->on('konumlar')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['urun_id'], 'sarf_stok_durumu_ibfk_1')->references(['urun_id'])->on('urun_katalogu')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarf_stok_durumu', function (Blueprint $table) {
            $table->dropForeign('fk_sarf_konum');
            $table->dropForeign('sarf_stok_durumu_ibfk_1');
        });
    }
};
