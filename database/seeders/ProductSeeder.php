<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ==================== 1. MINYAK GORENG (category_id: 1) ====================
            [
                'name' => 'Minyak Fortune', 'category_id' => 1, 'unit' => 'dus',
                'sell_price' => 242500, 'base_cost' => 235000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Minyak Alibaba', 'category_id' => 1, 'unit' => 'dus',
                'sell_price' => 235300, 'base_cost' => 228000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Minyak Kita', 'category_id' => 1, 'unit' => 'dus',
                'sell_price' => 221000, 'base_cost' => 214000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],

            // ==================== 2. GULA (category_id: 2) ====================
            [
                'name' => 'Gula Pasir', 'category_id' => 2, 'unit' => 'sak',
                'sell_price' => 795000, 'base_cost' => 775000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 50]],
            ],

            // ==================== 3. TEPUNG (category_id: 3) ====================
            [
                'name' => 'TP Beras', 'category_id' => 3, 'unit' => 'dus',
                'sell_price' => 159200, 'base_cost' => 150000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Ketan', 'category_id' => 3, 'unit' => 'dus',
                'sell_price' => 355000, 'base_cost' => 340000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Payung', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 180000, 'base_cost' => 170000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 25]],
            ],
            [
                'name' => 'TP Cakra', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 226000, 'base_cost' => 215000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 25]],
            ],
            [
                'name' => 'TP Lencana Merah', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 184000, 'base_cost' => 174000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 25]],
            ],
            [
                'name' => 'TP Segitiga', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 221000, 'base_cost' => 210000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 25]],
            ],
            [
                'name' => 'Oka Sak', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 297000, 'base_cost' => 285000,
                'price_breakdowns' => [['unit' => 'kg', 'qty' => 25]],
            ],
            [
                'name' => 'TP Lombok 1/4', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 314800, 'base_cost' => 300000,
                'price_breakdowns' => [['unit' => 'bal', 'qty' => 8], ['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Lombok Bumbu', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 242800, 'base_cost' => 230000,
                'price_breakdowns' => [['unit' => 'bal', 'qty' => 6], ['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Lombok Serbaguna', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 282700, 'base_cost' => 270000,
                'price_breakdowns' => [['unit' => 'bal', 'qty' => 7], ['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Buncis', 'category_id' => 3, 'unit' => 'sak',
                'sell_price' => 251700, 'base_cost' => 240000,
                'price_breakdowns' => [['unit' => 'bal', 'qty' => 10], ['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'Oka Bal 500gr', 'category_id' => 3, 'unit' => 'bal',
                'sell_price' => 142000, 'base_cost' => 134000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'Oka Bal 200gr', 'category_id' => 3, 'unit' => 'bal',
                'sell_price' => 55200, 'base_cost' => 50000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'TP Jempol', 'category_id' => 3, 'unit' => 'dus',
                'sell_price' => 135200, 'base_cost' => 125000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 100]],
            ],
            [
                'name' => 'TP Sasa', 'category_id' => 3, 'unit' => 'dus',
                'sell_price' => 121800, 'base_cost' => 114000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 15]],
            ],

            // ==================== 4. MIE (category_id: 4) ====================
            [
                'name' => 'Mi Sedap Goreng', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 112800, 'base_cost' => 105000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Mi Sedap Soto', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 107200, 'base_cost' => 100000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Bijag Hijau', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 72500, 'base_cost' => 67000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Bijag Merah', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 71800, 'base_cost' => 66000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Bijag Ungu', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 73700, 'base_cost' => 68000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Padamu M', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 83300, 'base_cost' => 77000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Padamu Oren', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 83300, 'base_cost' => 77000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'Eko Mi Pak', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 54300, 'base_cost' => 49000,
                'price_breakdowns' => [['unit' => 'pack', 'qty' => 6]],
            ],
            [
                'name' => 'Eko Mi Renteng', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 64300, 'base_cost' => 59000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Mie Sedap Korean', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 112800, 'base_cost' => 105000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Mie Sedap Ayam Bawang', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 107300, 'base_cost' => 100000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Mie Sedap Kari SP', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 107300, 'base_cost' => 100000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Mie Sukses Ayam Kecap', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 84200, 'base_cost' => 78000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Mie Sukses Ayam Kremes', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 84200, 'base_cost' => 78000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Mimora Pipih', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 50300, 'base_cost' => 45000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Mi Kering Mimora', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 49700, 'base_cost' => 44000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Cuka Meja 150ml', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 153800, 'base_cost' => 140000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Cuka Meja 80ml', 'category_id' => 4, 'unit' => 'dus',
                'sell_price' => 229300, 'base_cost' => 215000,
                'price_breakdowns' => null,
            ],

            // ==================== 5. BUMBU (category_id: 5) ====================
            [
                'name' => 'Masako Ayam', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 259200, 'base_cost' => 245000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 60]],
            ],
            [
                'name' => 'Masako Sapi', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 259200, 'base_cost' => 245000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 60]],
            ],
            [
                'name' => 'Royco Sapi / Ayam', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 210500, 'base_cost' => 198000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 60]],
            ],
            [
                'name' => 'Bango Refil', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 109300, 'base_cost' => 102000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 144]],
            ],
            [
                'name' => 'Bango Renteng', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 109300, 'base_cost' => 102000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 48]],
            ],
            [
                'name' => 'Motto Mobil 50gr', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 127300, 'base_cost' => 118000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 60]],
            ],
            [
                'name' => 'Motto Mobil 250gr', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 393200, 'base_cost' => 375000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Kecap Sedap 5+1', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 60300, 'base_cost' => 55000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 48]],
            ],
            [
                'name' => 'Kecap Sedap 250gr', 'category_id' => 5, 'unit' => 'pcs',
                'sell_price' => 8000, 'base_cost' => 7000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Micin Sasa 2000', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 419200, 'base_cost' => 398000,
                'price_breakdowns' => [['unit' => 'pak', 'qty' => 12], ['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'Micin Sasa 500', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 235300, 'base_cost' => 220000,
                'price_breakdowns' => [['unit' => 'pak', 'qty' => 18], ['unit' => 'lembar', 'qty' => 5], ['unit' => 'pcs', 'qty' => 6]],
            ],
            [
                'name' => 'Micin Sasa 5000', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 349300, 'base_cost' => 330000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 80]],
            ],
            [
                'name' => 'Micin Sasa 10.000', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 423500, 'base_cost' => 405000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Kerupuk Udang Vita Rasa', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 99700, 'base_cost' => 92000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 20]],
            ],
            [
                'name' => 'Kaldu Sedap Ayam', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 128700, 'base_cost' => 120000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 30]],
            ],
            [
                'name' => 'Kaldu Sedap Sapi', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 128700, 'base_cost' => 120000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 30]],
            ],
            [
                'name' => 'Santan Sasa', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 123200, 'base_cost' => 114000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 32]],
            ],
            [
                'name' => 'Desaku Bawang Putih', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 338700, 'base_cost' => 320000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 33]],
            ],
            [
                'name' => 'Desaku Kunyit', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 338700, 'base_cost' => 320000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Desaku Marinasi', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 339300, 'base_cost' => 320000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Desaku Ketumbar', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 152300, 'base_cost' => 142000,
                'price_breakdowns' => null,
            ],
            [
                'name' => 'Ladaku', 'category_id' => 5, 'unit' => 'dus',
                'sell_price' => 450300, 'base_cost' => 430000,
                'price_breakdowns' => null,
            ],

            // ==================== 6. KOPI & SUSU (category_id: 6) ====================
            [
                'name' => 'Luwak White', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 175000, 'base_cost' => 163000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Kapal Api Mini (6.5)', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 182700, 'base_cost' => 170000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 20]],
            ],
            [
                'name' => 'Kapal Api Mix', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 197200, 'base_cost' => 185000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Kapal Api MK', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 325500, 'base_cost' => 310000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 50]],
            ],
            [
                'name' => 'Goodday Capucino', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 237300, 'base_cost' => 224000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Goodday Freeze', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 266800, 'base_cost' => 252000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Top Gula Aren', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 199200, 'base_cost' => 188000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Top Susu 12+1', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 148800, 'base_cost' => 139000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Bendera Rtg Putih', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 160000, 'base_cost' => 150000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 20]],
            ],
            [
                'name' => 'Bendera Rtg Coklat', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 160000, 'base_cost' => 150000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 20]],
            ],
            [
                'name' => 'Top White', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 130800, 'base_cost' => 122000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Top Plus', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 95500, 'base_cost' => 88000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 12]],
            ],
            [
                'name' => 'Luwak Mini 6.5', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 160300, 'base_cost' => 150000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 20]],
            ],
            [
                'name' => 'Ya SP Gelas 120gr', 'category_id' => 6, 'unit' => 'dus',
                'sell_price' => 197200, 'base_cost' => 185000,
                'price_breakdowns' => null,
            ],

            // ==================== 7. MINUMAN (category_id: 7) ====================
            [
                'name' => 'Aqua Besar', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 51800, 'base_cost' => 46000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Aqua Tanggung', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 45000, 'base_cost' => 40000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 24]],
            ],
            [
                'name' => 'Aqua Gelas', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 34500, 'base_cost' => 30000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 48]],
            ],
            [
                'name' => 'Mizone', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 45300, 'base_cost' => 40000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 24]],
            ],
            [
                'name' => 'Teh Rio', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 17800, 'base_cost' => 15000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Sariwangi Kotak', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 260300, 'base_cost' => 245000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 48]],
            ],
            [
                'name' => 'Sariwangi Renteng', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 230300, 'base_cost' => 218000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 48]],
            ],
            [
                'name' => 'Teh Pucuk', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 60500, 'base_cost' => 54000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 24]],
            ],
            [
                'name' => 'Power F', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 18300, 'base_cost' => 15500,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Ale Ale', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 18800, 'base_cost' => 16000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Kopikap 2000', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 35300, 'base_cost' => 31000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Okky Jelly 1000', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 19700, 'base_cost' => 17000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Floridina', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 31200, 'base_cost' => 27000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Milku Stroberi', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 34300, 'base_cost' => 30000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Milku Coklat', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 34300, 'base_cost' => 30000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Golda Latte', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 35800, 'base_cost' => 31000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Al Ahya', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 16200, 'base_cost' => 13500,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'Kopi Nongkrong', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 19700, 'base_cost' => 17000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Aquviva 700ml', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 20800, 'base_cost' => 18000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Aquviva 1600ml', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 23300, 'base_cost' => 20000,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 6]],
            ],
            [
                'name' => 'Iso Plus 350ml', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 29300, 'base_cost' => 25500,
                'price_breakdowns' => [['unit' => 'botol', 'qty' => 12]],
            ],
            [
                'name' => 'Tea Jus Gula Batu', 'category_id' => 7, 'unit' => 'dus',
                'sell_price' => 108300, 'base_cost' => 99000,
                'price_breakdowns' => null,
            ],

            // ==================== 8. SABUN & DETERJEN (category_id: 8) ====================
            [
                'name' => 'Boom Cinta', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 104500, 'base_cost' => 96000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 36]],
            ],
            [
                'name' => 'Soklin Liquid', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 47800, 'base_cost' => 42000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 130]],
            ],
            [
                'name' => 'Soklin PK', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 57800, 'base_cost' => 51000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 72]],
            ],
            [
                'name' => 'Soklin GM', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 101700, 'base_cost' => 93000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Rinso GM', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 69200, 'base_cost' => 62000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 18]],
            ],
            [
                'name' => 'Rinso PK', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 54200, 'base_cost' => 48000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 72]],
            ],
            [
                'name' => 'Daia GM 5000', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 102700, 'base_cost' => 94000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Daia PK', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 57700, 'base_cost' => 51000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 72]],
            ],
            [
                'name' => 'Mama Lime 2000', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 36500, 'base_cost' => 32000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Molto 500', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 137300, 'base_cost' => 126000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 32]],
            ],
            [
                'name' => 'Soklin Pewangi', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 98200, 'base_cost' => 90000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 36]],
            ],
            [
                'name' => 'Ekonomi Cair', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 37200, 'base_cost' => 32500,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 36]],
            ],
            [
                'name' => 'Soklin Ekonomis', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 100300, 'base_cost' => 92000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 24]],
            ],
            [
                'name' => 'Royale 500', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 103300, 'base_cost' => 95000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 32]],
            ],
            [
                'name' => 'ABC Pencuci Piring', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 55300, 'base_cost' => 49000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 36]],
            ],
            [
                'name' => 'Ekonomi Cream', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 52500, 'base_cost' => 46000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 12]],
            ],
            [
                'name' => 'SB Nuvo 3+1', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 145800, 'base_cost' => 135000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 72]],
            ],
            [
                'name' => 'SB Giv', 'category_id' => 8, 'unit' => 'dus',
                'sell_price' => 187800, 'base_cost' => 175000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 72]],
            ],

            // ==================== 9. SHAMPOO (category_id: 9) ====================
            [
                'name' => 'SP Clear', 'category_id' => 9, 'unit' => 'dus',
                'sell_price' => 348200, 'base_cost' => 328000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 40]],
            ],
            [
                'name' => 'SP Lifebuoy', 'category_id' => 9, 'unit' => 'dus',
                'sell_price' => 85200, 'base_cost' => 77000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],
            [
                'name' => 'SP Zinc', 'category_id' => 9, 'unit' => 'dus',
                'sell_price' => 94200, 'base_cost' => 86000,
                'price_breakdowns' => [['unit' => 'renceng', 'qty' => 21]],
            ],
            [
                'name' => 'SP Sunsilk', 'category_id' => 9, 'unit' => 'dus',
                'sell_price' => 348200, 'base_cost' => 328000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 40]],
            ],

            // ==================== 10. OBAT NYAMUK (category_id: 10) ====================
            [
                'name' => 'Obat Nyamuk Sapi', 'category_id' => 10, 'unit' => 'dus',
                'sell_price' => 257300, 'base_cost' => 240000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 60]],
            ],
            [
                'name' => 'Obat Nyamuk Sapi Jumbo', 'category_id' => 10, 'unit' => 'dus',
                'sell_price' => 287300, 'base_cost' => 270000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 60]],
            ],
            [
                'name' => 'Obat Nyamuk Kingkong', 'category_id' => 10, 'unit' => 'dus',
                'sell_price' => 260500, 'base_cost' => 245000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 60]],
            ],
            [
                'name' => 'Autan', 'category_id' => 10, 'unit' => 'dus',
                'sell_price' => 403500, 'base_cost' => 380000,
                'price_breakdowns' => [['unit' => 'pcs', 'qty' => 60]],
            ],
        ];

        // Ambil gudang default untuk mencatat stok awal produk
        $defaultWarehouse = Warehouse::first();

        foreach ($products as $i => $item) {
            $sku = 'PRD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $slug = Str::slug($item['name']);

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'sku' => $sku,
                    'barcode' => '899' . str_pad((string)($i + 1), 10, '0', STR_PAD_LEFT),
                    'name' => $item['name'],
                    'slug' => $slug,
                    'brand' => explode(' ', $item['name'])[0],
                    'category_id' => $item['category_id'],
                    'supplier_id' => null,
                    'unit' => $item['unit'],
                    'weight' => 1.00,
                    'price_breakdowns' => $item['price_breakdowns'] ?? null,
                    'min_purchase' => 1,
                    'base_cost' => $item['base_cost'],
                    'sell_price' => $item['sell_price'],
                    'min_stock' => 5, // Minimal stok 5 untuk seluruh produk
                    'is_active' => true,
                ]
            );

            // Inisialisasi stok 10 di pivot product_warehouse jika gudang ada
            if ($defaultWarehouse) {
                DB::table('product_warehouse')->updateOrInsert(
                    [
                        'product_id' => $product->id,
                        'warehouse_id' => $defaultWarehouse->id,
                    ],
                    [
                        'stock' => 10, // Stok diatur 10
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // Tambahan Varian untuk Power F (stok tiap varian 10)
        $powerF = Product::where('name', 'Power F')->first();
        if ($powerF) {
            foreach (['Ungu', 'Merah', 'Kuning'] as $idx => $color) {
                ProductVariant::updateOrCreate(
                    ['product_id' => $powerF->id, 'name' => $color],
                    [
                        'extra_price' => 0,
                        'stock' => 10,
                        'is_active' => true,
                        'sort_order' => $idx,
                    ]
                );
            }
        }
    }
}