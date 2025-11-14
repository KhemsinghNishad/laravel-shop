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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
             $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('most_use')->nullable();
            $table->integer('max_user')->nullable();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('minimum_amount')->nullable();
            $table->double('discount_amount', 10, 2);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
