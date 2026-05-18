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
        Schema::table('arizalar', function (Blueprint $table) {
            $table->foreign(['demirbas_id'], 'arizalar_ibfk_1')->references(['demirbas_id'])->on('demirbaslar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['bildiren_kullanici_id'], 'arizalar_ibfk_2')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arizalar', function (Blueprint $table) {
            $table->dropForeign('arizalar_ibfk_1');
            $table->dropForeign('arizalar_ibfk_2');
        });
    }
};
