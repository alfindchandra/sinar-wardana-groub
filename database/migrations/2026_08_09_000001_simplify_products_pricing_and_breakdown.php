<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sederhanakan struktur harga produk:
     * - Hapus harga khusus per tipe customer (agent/distributor/store) — satu-satunya
     *   harga checkout adalah `sell_price` (Harga Jual Umum, per satuan utama: Sak/Dus).
     * - Hapus `content_per_unit` lama (ambigu), ganti dengan dua rasio breakdown yang jelas:
     *   `content_per_bal` (isi Bal dalam 1 Sak/Dus) dan `pcs_per_bal` (isi Pcs dalam 1 Bal).
     *   Dipakai untuk menghitung estimasi harga per Bal & per Pcs di halaman detail produk
     *   (informasi saja, TIDAK bisa dibeli terpisah).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['distributor_price', 'agent_price', 'store_price', 'content_per_unit']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('content_per_bal')->nullable()->comment('Isi Bal dalam 1 Sak/Dus, mis. 8');
            $table->unsignedInteger('pcs_per_bal')->nullable()->comment('Isi Pcs dalam 1 Bal, mis. 20');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['content_per_bal', 'pcs_per_bal']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('distributor_price', 15, 2)->default(0);
            $table->decimal('agent_price', 15, 2)->default(0);
            $table->decimal('store_price', 15, 2)->default(0);
            $table->integer('content_per_unit')->nullable();
        });
    }
};
