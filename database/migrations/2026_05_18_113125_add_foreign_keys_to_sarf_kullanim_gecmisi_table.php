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
        Schema::table('sarf_kullanim_gecmisi', function (Blueprint $table) {
            $table->foreign(['urun_id'], 'sarf_kullanim_gecmisi_ibfk_1')->references(['urun_id'])->on('urun_katalogu')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kullanici_id'], 'sarf_kullanim_gecmisi_ibfk_2')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarf_kullanim_gecmisi', function (Blueprint $table) {
            $table->dropForeign('sarf_kullanim_gecmisi_ibfk_1');
            $table->dropForeign('sarf_kullanim_gecmisi_ibfk_2');
        });
    }
};
