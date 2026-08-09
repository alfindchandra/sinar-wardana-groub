<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('online_order_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')
                ->constrained('product_variants')->onDelete('set null');
            // Snapshot nama varian saat order dibuat, supaya histori tetap utuh
            // walau varian di master data diubah/dihapus di kemudian hari.
            $table->string('product_variant_name', 100)->nullable()->after('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('online_order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_variant_id');
            $table->dropColumn('product_variant_name');
        });
    }
};
