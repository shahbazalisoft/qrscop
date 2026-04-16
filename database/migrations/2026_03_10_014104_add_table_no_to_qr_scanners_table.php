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
        Schema::table('qr_scanners', function (Blueprint $table) {
            $table->string('table_no', 20)->nullable()->after('qr_scanner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_scanners', function (Blueprint $table) {
            $table->dropColumn('table_no');
        });
    }
};
