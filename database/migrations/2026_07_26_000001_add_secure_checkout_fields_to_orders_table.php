<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->unique()->after('order_number');
            $table->uuid('checkout_token')->nullable()->unique()->after('access_token');
            $table->timestamp('reserved_until')->nullable()->after('status');
        });

        DB::table('orders')->orderBy('id')->each(function ($order): void {
            DB::table('orders')->where('id', $order->id)->update([
                'access_token' => Str::random(48),
                'reserved_until' => $order->status === 'new' ? now()->addHours(24) : null,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['access_token', 'checkout_token', 'reserved_until']);
        });
    }
};
