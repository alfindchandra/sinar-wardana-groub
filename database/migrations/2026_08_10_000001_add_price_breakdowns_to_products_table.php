<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ganti breakdown harga yang tadinya kaku (cuma 2 level: Bal & Pcs) menjadi
     * daftar breakdown bebas (`price_breakdowns`, JSON) — admin bisa pilih sendiri
     * satuan (Dus/Bal/Pcs/dst) dan jumlahnya, serta menambah level sebanyak yang perlu.
     *
     * Format price_breakdowns: [{"unit": "bal", "qty": 8}, {"unit": "pcs", "qty": 20}]
     * Artinya: 1 Sak/Dus = 8 Bal, lalu 1 Bal = 20 Pcs (dihitung berurutan/kumulatif).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('price_breakdowns')->nullable()->after('pcs_per_bal');
        });

        // Migrasikan data lama (content_per_bal + pcs_per_bal) ke format baru,
        // supaya produk yang sudah punya breakdown tidak kehilangan datanya.
        DB::table('products')
            ->whereNotNull('content_per_bal')
            ->orderBy('id')
            ->chunk(200, function ($products) {
                foreach ($products as $product) {
                    $steps = [];

                    if ($product->content_per_bal) {
                        $steps[] = ['unit' => 'bal', 'qty' => (int) $product->content_per_bal];
                    }

                    if ($product->pcs_per_bal) {
                        $steps[] = ['unit' => 'pcs', 'qty' => (int) $product->pcs_per_bal];
                    }

                    if (! empty($steps)) {
                        DB::table('products')
                            ->where('id', $product->id)
                            ->update(['price_breakdowns' => json_encode($steps)]);
                    }
                }
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['content_per_bal', 'pcs_per_bal']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('content_per_bal')->nullable()->comment('Isi Bal dalam 1 Sak/Dus, mis. 8');
            $table->unsignedInteger('pcs_per_bal')->nullable()->comment('Isi Pcs dalam 1 Bal, mis. 20');
        });

        // Ambil best-effort dari 2 level pertama saja (format lama cuma mendukung itu).
        DB::table('products')
            ->whereNotNull('price_breakdowns')
            ->orderBy('id')
            ->chunk(200, function ($products) {
                foreach ($products as $product) {
                    $steps = json_decode($product->price_breakdowns, true) ?: [];

                    DB::table('products')->where('id', $product->id)->update([
                        'content_per_bal' => $steps[0]['qty'] ?? null,
                        'pcs_per_bal' => $steps[1]['qty'] ?? null,
                    ]);
                }
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_breakdowns');
        });
    }
};
