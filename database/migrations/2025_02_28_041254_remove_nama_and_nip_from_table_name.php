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
        Schema::table('spts', function (Blueprint $table) {
            $table->dropColumn(['nama', 'nip']); // Hapus kolom yang tidak diperlukan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spts', function (Blueprint $table) {
            $table->string('nama')->after('user_id');
            $table->string('nip')->after('nama');
        });
    }
};
