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
        Schema::create('piutang_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_tempo');
            $table->bigInteger('bayar');
            $table->bigInteger('sisa');
            $table->bigInteger('id_pengeluaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang');
    }
};
