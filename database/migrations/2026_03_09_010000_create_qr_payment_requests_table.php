<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('package_id');
            $table->string('transaction_reference')->nullable();
            $table->string('payment_screenshot')->nullable();
            $table->decimal('amount', 24, 2)->default(0);
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_payment_requests');
    }
};
