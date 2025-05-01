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
        Schema::create('thing_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thing_id')->constrained()->onDelete('cascade');
            $table->foreignId('receipt_id')->constrained()->onDelete('cascade');
            $table->integer('count')->default(1);
            $table->string('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thing_receipts');
    }
};
