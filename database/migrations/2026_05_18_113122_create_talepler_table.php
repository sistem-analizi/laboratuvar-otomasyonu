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
        Schema::create('talepler', function (Blueprint $table) {
            $table->integer('talep_id', true);
            $table->integer('kullanici_id')->nullable()->index();
            $table->integer('urun_id')->nullable()->index();
            $table->timestamp('talep_tarihi')->nullable()->useCurrent();
            $table->date('istenen_kullanim_tarihi');
            $table->enum('talep_durumu', ['Bekliyor', 'Onaylandi', 'Reddedildi', 'Iptal'])->nullable()->default('Bekliyor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talepler');
    }
};
