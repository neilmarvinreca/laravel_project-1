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
        Schema::create('deployed_items', function (Blueprint $table) {
            $table->string('deployedID')->primary();
            $table->string('itemName');
            $table->text('itemDescription')->nullable();
            $table->dateTime('dateAcquired');
            $table->decimal('cost', 15, 2);
            $table->string('itemCategory');
            $table->string('qrCode')->unique();
            $table->unsignedBigInteger('departmentID');
            $table->date('dateDeployed');
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('departmentID')->references('departmentID')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployed_items');
    }
}; 