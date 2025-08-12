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
        if (Schema::hasColumn('deployed_items', 'qrCode') && !Schema::hasColumn('deployed_items', 'qr_code')) {
            Schema::table('deployed_items', function (Blueprint $table) {
                $table->renameColumn('qrCode', 'qr_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('deployed_items', 'qr_code') && !Schema::hasColumn('deployed_items', 'qrCode')) {
            Schema::table('deployed_items', function (Blueprint $table) {
                $table->renameColumn('qr_code', 'qrCode');
            });
        }
    }
};
