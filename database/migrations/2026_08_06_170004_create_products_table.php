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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 50)->nullable()->index();
            $table->string('sku', 50)->unique();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('brand', 100)->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->enum('unit', ['dus', 'sak', 'bal', 'karung', 'pack'])->default('dus');
            $table->decimal('weight', 10, 2)->nullable();
            $table->integer('content_per_unit')->nullable();
            $table->text('description')->nullable();
            $table->integer('min_purchase')->default(1);
            $table->decimal('base_cost', 15, 2)->default(0);
            $table->decimal('sell_price', 15, 2)->default(0);
            $table->decimal('distributor_price', 15, 2)->default(0);
            $table->decimal('agent_price', 15, 2)->default(0);
            $table->decimal('store_price', 15, 2)->default(0);
            $table->integer('min_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
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
