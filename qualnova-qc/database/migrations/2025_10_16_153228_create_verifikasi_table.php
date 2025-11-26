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
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id('id_verifikasi');
            $table->foreignId('id_cacat')->constrained('data_cacat', 'id_cacat')->onDelete('cascade');
            $table->foreignId('qc_id')->constrained('users', 'id')->onDelete('cascade');
            $table->date('tanggal_verifikasi');
            $table->boolean('valid');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi');
    }
};
