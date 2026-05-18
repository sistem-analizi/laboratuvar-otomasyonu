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
        Schema::create('alt_kategori', function (Blueprint $table) {
            $table->integer('alt_kategori_id', true);
            $table->string('alt_kategori_adi', 100)->nullable();
            $table->integer('kategori_id')->nullable()->index('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alt_kategori');
    }
};
