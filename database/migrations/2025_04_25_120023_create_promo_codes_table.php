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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Код промокода
            $table->decimal('discount', 8, 2); // Скидка (например, 15.50)
            $table->enum('type', ['percentage', 'fixed']); // Тип скидки (процентная или фиксированная)
            $table->timestamp('expires_at')->nullable(); // Дата истечения срока действия
            $table->boolean('is_active')->default(true); // Статус промокода (активен или нет)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
