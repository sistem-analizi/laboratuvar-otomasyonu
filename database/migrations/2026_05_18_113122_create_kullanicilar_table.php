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
        Schema::create('kullanicilar', function (Blueprint $table) {
            $table->integer('kullanici_id', true);
            $table->string('ad', 60);
            $table->string('soyad', 50);
            $table->string('email')->nullable();
            $table->string('okul_no', 20)->unique('okul_no');
            $table->string('sifre', 25);
            $table->integer('rol_id')->nullable()->index('rol_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kullanicilar');
    }
};
