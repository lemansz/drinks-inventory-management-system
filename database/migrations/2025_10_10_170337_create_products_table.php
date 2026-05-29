<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Category::class);
            $table->decimal('cost_per_unit');
            $table->decimal('selling_price');
            $table->decimal('profit');
            $table->decimal('crates_available');
            $table->unsignedInteger('stock');
            $table->unsignedSmallInteger('pieces_per_crate');
            $table->string('supplier');
            $table->string('supplier_phone_no', 11);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
