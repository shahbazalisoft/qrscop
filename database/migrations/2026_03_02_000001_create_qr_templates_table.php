<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('style')->unique()->comment('Style number 1-5 mapping to CSS class');
            $table->boolean('status')->default(1)->comment('0-Inactive,1-Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_templates');
    }
};
