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
        Schema::table('opds', function (Blueprint $table) {
            $table->string('link')->after('contact'); // Menambahkan kolom 'link' setelah kolom 'contact'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opds', function (Blueprint $table) {
            $table->dropColumn('link'); // Menghapus kolom 'link' jika rollback migration
        });
    }
};
