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
        Schema::create('arizalar', function (Blueprint $table) {
            $table->integer('ariza_id', true);
            $table->integer('demirbas_id')->nullable()->index('demirbas_id');
            $table->integer('bildiren_kullanici_id')->nullable()->index('bildiren_kullanici_id');
            $table->text('ariza_aciklama')->nullable();
            $table->timestamp('tarih')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arizalar');
    }
};
