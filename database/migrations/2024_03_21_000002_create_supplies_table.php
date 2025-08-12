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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id('itemID');
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('acquired_at');
            $table->string('estimated_life')->nullable();
            $table->decimal('unit_cost', 15, 2);
            $table->integer('quantity');
            $table->decimal('amount', 15, 2);
            $table->foreignId('category_id')->constrained('categories', 'categoryID');
            $table->string('fund_code');
            $table->string('ppesubacc');
            $table->string('gl_code');
            $table->foreignId('added_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
}; 