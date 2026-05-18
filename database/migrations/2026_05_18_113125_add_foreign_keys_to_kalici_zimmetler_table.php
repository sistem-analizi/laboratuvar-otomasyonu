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
        Schema::table('kalici_zimmetler', function (Blueprint $table) {
            $table->foreign(['demirbas_id'], 'kalici_zimmetler_ibfk_1')->references(['demirbas_id'])->on('demirbaslar')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kullanici_id'], 'kalici_zimmetler_ibfk_2')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['veren_yetkili_id'], 'kalici_zimmetler_ibfk_3')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalici_zimmetler', function (Blueprint $table) {
            $table->dropForeign('kalici_zimmetler_ibfk_1');
            $table->dropForeign('kalici_zimmetler_ibfk_2');
            $table->dropForeign('kalici_zimmetler_ibfk_3');
        });
    }
};
