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
            // Rename existing columns
            // $table->renameColumn('itemName', 'name');
            // $table->renameColumn('itemDescription', 'description');
            // $table->renameColumn('dateAcquired', 'acquired_at');
            // $table->renameColumn('estimatedLife', 'estimated_life');
            // $table->renameColumn('cost', 'unit_cost');
            // $table->renameColumn('itemCategory', 'category_id');
            // $table->renameColumn('fundcode', 'fund_code');
            // $table->renameColumn('gl_code', 'gl_code');
            // $table->renameColumn('addedby', 'added_by');

            // Add new columns if they don't exist
            if (!Schema::hasColumn('supplies', 'location')) {
                $table->string('location')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('supplies', 'unit')) {
                $table->string('unit')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            // Drop new columns if they exist
            if (Schema::hasColumn('supplies', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('supplies', 'unit')) {
                $table->dropColumn('unit');
            }

            // Rename columns back to original names
            // $table->renameColumn('name', 'itemName');
            // $table->renameColumn('description', 'itemDescription');
            // $table->renameColumn('acquired_at', 'dateAcquired');
            // $table->renameColumn('estimated_life', 'estimatedLife');
            // $table->renameColumn('unit_cost', 'cost');
            // $table->renameColumn('category_id', 'itemCategory');
            // $table->renameColumn('fund_code', 'fundcode');
            // $table->renameColumn('gl_code', 'glcode');
            // $table->renameColumn('added_by', 'addedby');
        });
    }
};
