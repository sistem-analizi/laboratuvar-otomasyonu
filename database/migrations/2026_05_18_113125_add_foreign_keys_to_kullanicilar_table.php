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
        Schema::table('kullanicilar', function (Blueprint $table) {
            $table->foreign(['rol_id'], 'kullanicilar_ibfk_1')->references(['rol_id'])->on('roller')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kullanicilar', function (Blueprint $table) {
            $table->dropForeign('kullanicilar_ibfk_1');
        });
    }
};
