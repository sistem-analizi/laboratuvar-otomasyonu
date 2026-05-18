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
        Schema::create('oduncler', function (Blueprint $table) {
            $table->integer('odunc_id', true);
            $table->integer('demirbas_id')->index();
            $table->integer('kullanici_id')->index();
            $table->integer('veren_yetkili_id')->index();
            $table->integer('alan_yetkili_id')->nullable()->index('alan_yetkili_id');
            $table->dateTime('verilis_tarihi')->nullable()->useCurrent();
            $table->dateTime('planlanan_iade_tarihi');
            $table->dateTime('gerceklesen_iade_tarihi')->nullable();
            $table->enum('durum', ['Kullanimda', 'Iade Edildi', 'Gecikti', 'Kusurlu Iade'])->nullable()->default('Kullanimda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oduncler');
    }
};
