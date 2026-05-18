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
        Schema::table('talepler', function (Blueprint $table) {
            $table->foreign(['kullanici_id'], 'talepler_ibfk_1')->references(['kullanici_id'])->on('kullanicilar')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['urun_id'], 'talepler_ibfk_2')->references(['urun_id'])->on('urun_katalogu')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talepler', function (Blueprint $table) {
            $table->dropForeign('talepler_ibfk_1');
            $table->dropForeign('talepler_ibfk_2');
        });
    }
};
