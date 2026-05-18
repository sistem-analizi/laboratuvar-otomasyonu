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
        Schema::create('demirbaslar', function (Blueprint $table) {
            $table->integer('demirbas_id', true);
            $table->integer('urun_id')->nullable()->index('urun_id');
            $table->string('seri_no', 100)->nullable()->unique('seri_no');
            $table->integer('konum_id')->nullable()->index('konum_id');
            $table->enum('durum', ['Bosta', 'Zimmette', 'Arizali', 'Kayip'])->nullable()->default('Bosta');
            $table->decimal('fiyat', 10)->nullable();
            $table->date('gelis_tarihi')->nullable();
            $table->integer('fatura_id')->nullable()->index('fk_demirbas_fatura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demirbaslar');
    }
};
