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
        Schema::create('faturalar', function (Blueprint $table) {
            $table->integer('fatura_id', true);
            $table->integer('tedarik_id')->index('fk_fatura_tedarikci');
            $table->string('satici_firma', 200)->nullable();
            $table->timestamp('fatura_tarihi');
            $table->decimal('toplam_tutar', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturalar');
    }
};
