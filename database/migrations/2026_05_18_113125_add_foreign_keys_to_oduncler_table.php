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
        Schema::table('oduncler', function (Blueprint $table) {
            $table->foreign(['demirbas_id'], 'oduncler_ibfk_1')->references(['demirbas_id'])->on('demirbaslar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kullanici_id'], 'oduncler_ibfk_2')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['veren_yetkili_id'], 'oduncler_ibfk_3')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['alan_yetkili_id'], 'oduncler_ibfk_4')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oduncler', function (Blueprint $table) {
            $table->dropForeign('oduncler_ibfk_1');
            $table->dropForeign('oduncler_ibfk_2');
            $table->dropForeign('oduncler_ibfk_3');
            $table->dropForeign('oduncler_ibfk_4');
        });
    }
};
