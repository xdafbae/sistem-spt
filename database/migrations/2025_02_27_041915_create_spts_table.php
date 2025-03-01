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
        Schema::create('spts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_pengajuan');
            $table->text('dasar');
            $table->foreignId('user_id')->constrained();
            $table->string('nama');
            $table->string('nip');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('tujuan');
            $table->enum('status', [
                'Menunggu Verifikasi', 
                'Diverifikasi Oleh Operator', 
                'Dikembalikan',
                'Selesai'
            ])->default('Menunggu Verifikasi');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spts');
    }
};
