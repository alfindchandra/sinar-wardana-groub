<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Minyak Goreng (category_id: 1)
            [
                'sku' => 'PRD-0001', 'barcode' => '8992388100019',
                'name' => 'Bimoli Minyak Goreng 2L', 'slug' => 'bimoli-minyak-goreng-2l',
                'brand' => 'Bimoli', 'category_id' => 1, 'supplier_id' => 3,
                'unit' => 'dus', 'weight' => 12.00, 'content_per_unit' => 6,
                'min_purchase' => 1, 'base_cost' => 180000.00,
                'sell_price' => 198000.00, 'distributor_price' => 185000.00,
                'agent_price' => 190000.00, 'store_price' => 195000.00,
                'min_stock' => 20, 'is_active' => true,
            ],
            [
                'sku' => 'PRD-0002', 'barcode' => '8992388100026',
                'name' => 'Sunco Minyak Goreng 2L', 'slug' => 'sunco-minyak-goreng-2l',
                'brand' => 'Sunco', 'category_id' => 1, 'supplier_id' => 3,
                'unit' => 'dus', 'weight' => 12.00, 'content_per_unit' => 6,
                'min_purchase' => 1, 'base_cost' => 170000.00,
                'sell_price' => 188000.00, 'distributor_price' => 175000.00,
                'agent_price' => 180000.00, 'store_price' => 185000.00,
                'min_stock' => 20, 'is_active' => true,
            ],
            // Beras (category_id: 2)
            [
                'sku' => 'PRD-0003', 'barcode' => '8991102310017',
                'name' => 'Beras Raja Lele 25kg', 'slug' => 'beras-raja-lele-25kg',
                'brand' => 'Raja Lele', 'category_id' => 2, 'supplier_id' => null,
                'unit' => 'karung', 'weight' => 25.00, 'content_per_unit' => 1,
                'min_purchase' => 1, 'base_cost' => 310000.00,
                'sell_price' => 335000.00, 'distributor_price' => 315000.00,
                'agent_price' => 320000.00, 'store_price' => 328000.00,
                'min_stock' => 50, 'is_active' => true,
            ],
            [
                'sku' => 'PRD-0004', 'barcode' => '8991102310024',
                'name' => 'Beras Pandan Wangi 25kg', 'slug' => 'beras-pandan-wangi-25kg',
                'brand' => 'Pandan Wangi', 'category_id' => 2, 'supplier_id' => null,
                'unit' => 'karung', 'weight' => 25.00, 'content_per_unit' => 1,
                'min_purchase' => 1, 'base_cost' => 330000.00,
                'sell_price' => 358000.00, 'distributor_price' => 335000.00,
                'agent_price' => 342000.00, 'store_price' => 350000.00,
                'min_stock' => 50, 'is_active' => true,
            ],
            // Gula (category_id: 3)
            [
                'sku' => 'PRD-0005', 'barcode' => '8993388220013',
                'name' => 'Gula Pasir Gulaku 1kg', 'slug' => 'gula-pasir-gulaku-1kg',
                'brand' => 'Gulaku', 'category_id' => 3, 'supplier_id' => 3,
                'unit' => 'dus', 'weight' => 24.00, 'content_per_unit' => 24,
                'min_purchase' => 1, 'base_cost' => 360000.00,
                'sell_price' => 396000.00, 'distributor_price' => 370000.00,
                'agent_price' => 378000.00, 'store_price' => 388000.00,
                'min_stock' => 30, 'is_active' => true,
            ],
            // Tepung (category_id: 4)
            [
                'sku' => 'PRD-0006', 'barcode' => '8992761110019',
                'name' => 'Tepung Terigu Segitiga Biru 1kg', 'slug' => 'tepung-terigu-segitiga-biru-1kg',
                'brand' => 'Segitiga Biru', 'category_id' => 4, 'supplier_id' => null,
                'unit' => 'dus', 'weight' => 12.00, 'content_per_unit' => 12,
                'min_purchase' => 1, 'base_cost' => 142000.00,
                'sell_price' => 156000.00, 'distributor_price' => 146000.00,
                'agent_price' => 150000.00, 'store_price' => 153000.00,
                'min_stock' => 25, 'is_active' => true,
            ],
            // Mie Instan (category_id: 5)
            [
                'sku' => 'PRD-0007', 'barcode' => '8996001600016',
                'name' => 'Indomie Goreng', 'slug' => 'indomie-goreng',
                'brand' => 'Indomie', 'category_id' => 5, 'supplier_id' => 1,
                'unit' => 'dus', 'weight' => 5.00, 'content_per_unit' => 40,
                'min_purchase' => 1, 'base_cost' => 104000.00,
                'sell_price' => 112000.00, 'distributor_price' => 106000.00,
                'agent_price' => 108000.00, 'store_price' => 110000.00,
                'min_stock' => 100, 'is_active' => true,
            ],
            [
                'sku' => 'PRD-0008', 'barcode' => '8996001600023',
                'name' => 'Indomie Kuah Soto', 'slug' => 'indomie-kuah-soto',
                'brand' => 'Indomie', 'category_id' => 5, 'supplier_id' => 1,
                'unit' => 'dus', 'weight' => 5.00, 'content_per_unit' => 40,
                'min_purchase' => 1, 'base_cost' => 100000.00,
                'sell_price' => 108000.00, 'distributor_price' => 102000.00,
                'agent_price' => 104000.00, 'store_price' => 106000.00,
                'min_stock' => 80, 'is_active' => true,
            ],
            [
                'sku' => 'PRD-0009', 'barcode' => '8996001600030',
                'name' => 'Mie Sedaap Goreng', 'slug' => 'mie-sedaap-goreng',
                'brand' => 'Mie Sedaap', 'category_id' => 5, 'supplier_id' => 4,
                'unit' => 'dus', 'weight' => 5.00, 'content_per_unit' => 40,
                'min_purchase' => 1, 'base_cost' => 100000.00,
                'sell_price' => 108000.00, 'distributor_price' => 102000.00,
                'agent_price' => 104000.00, 'store_price' => 106000.00,
                'min_stock' => 80, 'is_active' => true,
            ],
            // Kopi & Teh (category_id: 6)
            [
                'sku' => 'PRD-0010', 'barcode' => '8992388330011',
                'name' => 'Kapal Api Special Mix', 'slug' => 'kapal-api-special-mix',
                'brand' => 'Kapal Api', 'category_id' => 6, 'supplier_id' => null,
                'unit' => 'dus', 'weight' => 3.00, 'content_per_unit' => 120,
                'min_purchase' => 1, 'base_cost' => 185000.00,
                'sell_price' => 204000.00, 'distributor_price' => 190000.00,
                'agent_price' => 195000.00, 'store_price' => 200000.00,
                'min_stock' => 30, 'is_active' => true,
            ],
            // Susu (category_id: 7)
            [
                'sku' => 'PRD-0011', 'barcode' => '8993200110018',
                'name' => 'Susu Indomilk Coklat 250ml', 'slug' => 'susu-indomilk-coklat-250ml',
                'brand' => 'Indomilk', 'category_id' => 7, 'supplier_id' => 1,
                'unit' => 'dus', 'weight' => 6.00, 'content_per_unit' => 24,
                'min_purchase' => 1, 'base_cost' => 96000.00,
                'sell_price' => 108000.00, 'distributor_price' => 99000.00,
                'agent_price' => 102000.00, 'store_price' => 105000.00,
                'min_stock' => 30, 'is_active' => true,
            ],
            // Minuman (category_id: 8)
            [
                'sku' => 'PRD-0012', 'barcode' => '8993200220019',
                'name' => 'Aqua Botol 600ml', 'slug' => 'aqua-botol-600ml',
                'brand' => 'Aqua', 'category_id' => 8, 'supplier_id' => null,
                'unit' => 'dus', 'weight' => 15.00, 'content_per_unit' => 24,
                'min_purchase' => 1, 'base_cost' => 58000.00,
                'sell_price' => 65000.00, 'distributor_price' => 60000.00,
                'agent_price' => 62000.00, 'store_price' => 63500.00,
                'min_stock' => 50, 'is_active' => true,
            ],
            // Sabun & Deterjen (category_id: 9)
            [
                'sku' => 'PRD-0013', 'barcode' => '8999999044015',
                'name' => 'Rinso Anti Noda 900g', 'slug' => 'rinso-anti-noda-900g',
                'brand' => 'Rinso', 'category_id' => 9, 'supplier_id' => 2,
                'unit' => 'dus', 'weight' => 10.80, 'content_per_unit' => 12,
                'min_purchase' => 1, 'base_cost' => 192000.00,
                'sell_price' => 216000.00, 'distributor_price' => 198000.00,
                'agent_price' => 204000.00, 'store_price' => 210000.00,
                'min_stock' => 20, 'is_active' => true,
            ],
            [
                'sku' => 'PRD-0014', 'barcode' => '8999999044022',
                'name' => 'Sunlight Jeruk Nipis 800ml', 'slug' => 'sunlight-jeruk-nipis-800ml',
                'brand' => 'Sunlight', 'category_id' => 9, 'supplier_id' => 2,
                'unit' => 'dus', 'weight' => 9.60, 'content_per_unit' => 12,
                'min_purchase' => 1, 'base_cost' => 120000.00,
                'sell_price' => 138000.00, 'distributor_price' => 125000.00,
                'agent_price' => 130000.00, 'store_price' => 135000.00,
                'min_stock' => 20, 'is_active' => true,
            ],
            // Bumbu & Saos (category_id: 10)
            [
                'sku' => 'PRD-0015', 'barcode' => '8993175544019',
                'name' => 'Kecap Manis ABC 600ml', 'slug' => 'kecap-manis-abc-600ml',
                'brand' => 'ABC', 'category_id' => 10, 'supplier_id' => null,
                'unit' => 'dus', 'weight' => 7.20, 'content_per_unit' => 12,
                'min_purchase' => 1, 'base_cost' => 156000.00,
                'sell_price' => 174000.00, 'distributor_price' => 160000.00,
                'agent_price' => 165000.00, 'store_price' => 170000.00,
                'min_stock' => 15, 'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );

            // Create multi-tier pricing for each product
            $this->createPricing($product);
        }
    }

    private function createPricing(Product $product): void
    {
        // Retail pricing tiers (based on quantity)
        $retailPrices = [
            ['price_type' => 'retail', 'min_qty' => 1, 'max_qty' => 10, 'price' => $product->sell_price],
            ['price_type' => 'retail', 'min_qty' => 11, 'max_qty' => 25, 'price' => $product->sell_price * 0.98],
            ['price_type' => 'retail', 'min_qty' => 26, 'max_qty' => null, 'price' => $product->sell_price * 0.96],
        ];

        // Agen pricing tiers
        $agenPrices = [
            ['price_type' => 'agen', 'min_qty' => 1, 'max_qty' => 20, 'price' => $product->agent_price],
            ['price_type' => 'agen', 'min_qty' => 21, 'max_qty' => 50, 'price' => $product->agent_price * 0.98],
            ['price_type' => 'agen', 'min_qty' => 51, 'max_qty' => null, 'price' => $product->agent_price * 0.96],
        ];

        // Distributor pricing tiers
        $distributorPrices = [
            ['price_type' => 'distributor', 'min_qty' => 1, 'max_qty' => 50, 'price' => $product->distributor_price],
            ['price_type' => 'distributor', 'min_qty' => 51, 'max_qty' => null, 'price' => $product->distributor_price * 0.97],
        ];

        $allPrices = array_merge($retailPrices, $agenPrices, $distributorPrices);

        foreach ($allPrices as $priceData) {
            ProductPrice::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'price_type' => $priceData['price_type'],
                    'min_qty' => $priceData['min_qty'],
                ],
                array_merge($priceData, [
                    'product_id' => $product->id,
                    'price' => round($priceData['price']),
                    'is_active' => true,
                ])
            );
        }
    }
}
