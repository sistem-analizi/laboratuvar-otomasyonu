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
        Schema::create('urun_katalogu', function (Blueprint $table) {
            $table->integer('urun_id', true);
            $table->string('urun_adi', 100);
            $table->integer('alt_kategori_id')->nullable()->index('alt_kategori_id');
            $table->text('teknik_detay')->nullable();
            $table->integer('tip_id')->nullable()->index('fk_katalog_tip');
            $table->string('urun_kodu', 20)->nullable();
            $table->string('dolap_konumu', 100)->nullable();
            $table->string('resim_yolu')->nullable();
            $table->integer('marka_id')->nullable()->index('fk_urun_marka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urun_katalogu');
    }
};
