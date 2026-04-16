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
        Schema::table('menu_templates', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->text('description')->nullable()->after('slug');
            $table->string('tags')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_templates', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'tags']);
        });
    }
};
