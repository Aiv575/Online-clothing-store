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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_request_id')->constrained()->onDelete('cascade'); // Связь с заказом
            $table->foreignId('thing_id')->constrained()->onDelete('cascade'); // Связь с товаром
            $table->integer('quantity'); // Количество
            $table->string('size');
            $table->string('image')->nullable();
            $table->string('reason'); // Причина возврата
            $table->unsignedBigInteger('price'); // Цена за 1 шт.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
