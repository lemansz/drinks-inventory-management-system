<?php

use App\Models\Product;
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
        Schema::create('restock_logs', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class);
            $table->integer('crates');
            $table->integer('unit_cost');
            $table->integer('total_cost');
            $table->integer('unit_per_crate');
            $table->integer('total_unit');
            $table->timestamp('restocked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_logs');
    }
};
