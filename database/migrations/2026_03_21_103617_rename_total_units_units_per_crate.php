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
        Schema::table('restock_logs', function (Blueprint $table) {
            $table->renameColumn('unit_per_crate', 'units_per_crate');
        });

        Schema::table('restock_logs', function (Blueprint $table) {
            $table->renameColumn('total_unit', 'total_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restock_logs', function (Blueprint $table) {
            $table->renameColumn('total_units', 'total_unit');
            $table->renameColumn('units_per_crate', 'unit_per_crate');
        });
    }
};
