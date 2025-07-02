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
        Schema::table('supplies', function (Blueprint $table) {
            $table->string('location')->nullable()->after('unit_cost');
            $table->string('supplier')->nullable()->after('location');
            $table->string('supplier_contact')->nullable()->after('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn(['location', 'supplier', 'supplier_contact']);
        });
    }
}; 