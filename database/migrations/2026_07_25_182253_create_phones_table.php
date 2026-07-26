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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('price');
            $table->unsignedInteger('old_price')->nullable();
            $table->string('image_url');
            $table->string('ram');
            $table->string('storage');
            $table->string('display')->nullable();
            $table->string('processor')->nullable();
            $table->string('camera')->nullable();
            $table->string('battery')->nullable();
            $table->string('os')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
