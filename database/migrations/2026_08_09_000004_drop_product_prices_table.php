<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel product_prices (harga bertingkat per tipe customer: retail/agen/distributor)
     * dihapus karena aturan bisnis baru: hanya ada SATU harga checkout (`sell_price`,
     * Harga Jual Umum). Breakdown per Bal/Pcs kini dihitung otomatis dari
     * products.content_per_bal & products.pcs_per_bal, bukan disimpan manual.
     */
    public function up(): void
    {
        Schema::dropIfExists('product_prices');
    }

    public function down(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('price_type', ['retail', 'agen', 'distributor']);
            $table->integer('min_qty')->default(1);
            $table->integer('max_qty')->nullable();
            $table->decimal('price', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'price_type', 'min_qty']);
        });
    }
};
