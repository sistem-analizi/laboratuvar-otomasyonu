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
        Schema::create('kalici_zimmetler', function (Blueprint $table) {
            $table->integer('zimmet_id', true);
            $table->integer('demirbas_id')->index('kalici_zimmetler_ibfk_1');
            $table->integer('kullanici_id')->index('kullanici_id');
            $table->integer('veren_yetkili_id')->index('veren_yetkili_id');
            $table->dateTime('verilis_tarihi')->nullable()->useCurrent();
            $table->boolean('aktif_mi')->nullable()->default(true);
            $table->text('aciklama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalici_zimmetler');
    }
};
