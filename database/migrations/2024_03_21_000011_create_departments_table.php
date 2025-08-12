<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('departmentID');
            $table->string('locationcode')->unique();
            $table->string('officename');
            $table->unsignedBigInteger('accountableper');
            $table->string('description');
            $table->timestamps();

            $table->foreign('accountableper')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
}; 