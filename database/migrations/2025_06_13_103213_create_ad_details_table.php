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
        Schema::create('ad_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained()->onDelete('cascade');
            $table->integer('clicks');
            $table->integer('calls');
            $table->string('note')->nullable();
            $table->float('performance')->default(0);
            $table->decimal('budget_spent', 10, 2)->default(0.00); // Total ad spend
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_details');
    }
};