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
        Schema::table('faturalar', function (Blueprint $table) {
            $table->foreign(['tedarik_id'], 'fk_fatura_tedarikci')->references(['tedarik_id'])->on('tedarik_kaynaklari')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturalar', function (Blueprint $table) {
            $table->dropForeign('fk_fatura_tedarikci');
        });
    }
};
