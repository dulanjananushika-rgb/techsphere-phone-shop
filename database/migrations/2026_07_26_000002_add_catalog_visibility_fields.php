<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('is_featured');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('phones', fn (Blueprint $table) => $table->dropColumn('is_active'));
        Schema::table('accessories', fn (Blueprint $table) => $table->dropColumn('is_active'));
    }
};
