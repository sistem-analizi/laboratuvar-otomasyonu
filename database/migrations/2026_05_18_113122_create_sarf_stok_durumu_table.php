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
        Schema::create('sarf_stok_durumu', function (Blueprint $table) {
            $table->integer('stok_id', true);
            $table->integer('urun_id')->nullable()->unique();
            $table->decimal('toplam_miktar', 10);
            $table->integer('konum_id')->nullable()->index('fk_sarf_konum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarf_stok_durumu');
    }
};
