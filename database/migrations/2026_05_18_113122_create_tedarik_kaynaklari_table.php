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
        Schema::create('tedarik_kaynaklari', function (Blueprint $table) {
            $table->integer('tedarik_id', true);
            $table->string('kaynak_adi', 100);
            $table->string('iletisim_bilgisi', 150)->nullable();
            $table->integer('tedarik_tip_id')->nullable()->index('fk_tedarik_tipi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tedarik_kaynaklari');
    }
};
