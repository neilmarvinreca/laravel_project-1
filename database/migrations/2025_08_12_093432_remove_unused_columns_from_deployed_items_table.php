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
        Schema::table('deployed_items', function (Blueprint $table) {
            $columns = ['purpose', 'condition', 'next_maintenance_date', 'warranty_end_date', 'deployed_by'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('deployed_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deployed_items', function (Blueprint $table) {
            $table->text('purpose')->nullable()->after('status');
            $table->string('condition', 20)->nullable()->after('purpose');
            $table->dateTime('next_maintenance_date')->nullable()->after('condition');
            $table->date('warranty_end_date')->nullable()->after('next_maintenance_date');
            $table->foreignId('deployed_by')->nullable()->after('warranty_end_date')->constrained('users');
        });
    }
};
