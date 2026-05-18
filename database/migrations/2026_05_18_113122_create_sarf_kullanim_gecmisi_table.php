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
        Schema::create('sarf_kullanim_gecmisi', function (Blueprint $table) {
            $table->integer('islem_id', true);
            $table->integer('urun_id')->nullable()->index();
            $table->integer('kullanici_id')->nullable()->index();
            $table->decimal('kullanilan_miktar', 10);
            $table->timestamp('islem_tarihi')->nullable()->useCurrent();
            $table->text('aciklama')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarf_kullanim_gecmisi');
    }
};
