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
        Schema::create('data_cacat', function (Blueprint $table) {
            $table->id('id_cacat');
            $table->date('tanggal');
            $table->string('shift');
            $table->string('jenis_kain')->nullable();
            $table->string('lokasi_mesin');
            $table->string('jenis_cacat');
            $table->string('foto_bukti')->nullable();
            
            $table->boolean('status_verifikasi')->default(false);
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_jenis')->constrained('jenis_cacat', 'id_jenis')->onDelete('cascade');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_cacat');
    }
};
