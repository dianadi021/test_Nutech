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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('norm')->unique()->nullable();
            $table->unsignedBigInteger('id_penduduk');
            $table->foreign('id_penduduk')->references('id')->on('penduduk')->onDelete('cascade');
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();
            $table->integer('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
