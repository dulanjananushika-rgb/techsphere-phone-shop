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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_reference');
            $table->string('invoice_number')->nullable()->unique()->after('order_number');
            $table->timestamp('invoiced_at')->nullable()->after('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_reference', 'paid_at', 'invoice_number', 'invoiced_at']);
        });
    }
};
