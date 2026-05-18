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
        Schema::table('demirbaslar', function (Blueprint $table) {
            $table->foreign(['urun_id'], 'demirbaslar_ibfk_1')->references(['urun_id'])->on('urun_katalogu')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['konum_id'], 'demirbaslar_ibfk_2')->references(['konum_id'])->on('konumlar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['fatura_id'], 'fk_demirbas_fatura')->references(['fatura_id'])->on('faturalar')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demirbaslar', function (Blueprint $table) {
            $table->dropForeign('demirbaslar_ibfk_1');
            $table->dropForeign('demirbaslar_ibfk_2');
            $table->dropForeign('fk_demirbas_fatura');
        });
    }
};
