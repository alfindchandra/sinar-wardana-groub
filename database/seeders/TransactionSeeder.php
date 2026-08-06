<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Receivable;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPurchaseFlow();
        $this->seedSalesFlow();
        $this->seedPromos();
    }

    private function seedPurchaseFlow(): void
    {
        $warehouse = Warehouse::where('code', 'GDG-001')->first();
        $supplier = Supplier::where('code', 'SUP-0001')->first();
        $createdBy = User::where('email', 'gudang@sinarwardana.com')->first();

        if (! $warehouse || ! $supplier || ! $createdBy) {
            return;
        }

        $purchaseOrder = PurchaseOrder::firstOrCreate(
            ['po_number' => 'PO-20260807-001'],
            [
                'supplier_id' => $supplier->id,
                'warehouse_id' => $warehouse->id,
                'order_date' => now()->subDays(3)->toDateString(),
                'expected_date' => now()->addDays(2)->toDateString(),
                'status' => 'approved',
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'grand_total' => 0,
                'notes' => 'Pembelian awal untuk stok gudang utama',
                'created_by' => $createdBy->id,
                'approved_by' => $createdBy->id,
                'approved_at' => now(),
            ]
        );

        $products = Product::whereIn('sku', ['PRD-0001', 'PRD-0003', 'PRD-0007'])->get();
        $subtotal = 0;

        foreach ($products as $product) {
            $qty = $product->sku === 'PRD-0003' ? 5 : 10;
            $price = $product->base_cost;
            $lineTotal = $qty * $price;
            $subtotal += $lineTotal;

            PurchaseOrderItem::firstOrCreate(
                [
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                ],
                [
                    'qty' => $qty,
                    'unit' => $product->unit,
                    'price' => $price,
                    'discount' => 0,
                    'subtotal' => $lineTotal,
                    'notes' => 'Item pembelian awal',
                ]
            );
        }

        $purchaseOrder->update([
            'subtotal' => $subtotal,
            'tax' => round($subtotal * 0.11, 2),
            'grand_total' => $subtotal + round($subtotal * 0.11, 2),
        ]);

        $goodsReceipt = GoodsReceipt::firstOrCreate(
            ['gr_number' => 'GR-20260807-001'],
            [
                'purchase_order_id' => $purchaseOrder->id,
                'warehouse_id' => $warehouse->id,
                'received_date' => now()->subDay()->toDateString(),
                'status' => 'completed',
                'notes' => 'Penerimaan barang selesai',
                'received_by' => $createdBy->id,
            ]
        );

        foreach ($products as $product) {
            $qty = $product->sku === 'PRD-0003' ? 5 : 10;
            GoodsReceiptItem::firstOrCreate(
                [
                    'goods_receipt_id' => $goodsReceipt->id,
                    'product_id' => $product->id,
                ],
                [
                    'purchase_order_item_id' => $purchaseOrder->items()->where('product_id', $product->id)->value('id'),
                    'qty_ordered' => $qty,
                    'qty_received' => $qty,
                    'batch_number' => 'BATCH-' . $product->sku,
                    'expiry_date' => now()->addMonths(6)->toDateString(),
                    'notes' => 'Penerimaan otomatis',
                ]
            );

            $this->adjustStock($product, $warehouse, $qty, 'in', 'goods_receipt', $goodsReceipt->id, 'Stok masuk dari penerimaan barang');
        }
    }

    private function seedSalesFlow(): void
    {
        $warehouse = Warehouse::where('code', 'GDG-001')->first();
        $salesUser = User::where('email', 'sales1@sinarwardana.com')->first();
        $customer = Customer::where('code', 'CUST-0001')->first();

        if (! $warehouse || ! $salesUser || ! $customer) {
            return;
        }

        $salesOrder = SalesOrder::firstOrCreate(
            ['so_number' => 'SO-20260807-001'],
            [
                'customer_id' => $customer->id,
                'sales_person_id' => $customer->sales_person_id,
                'warehouse_id' => $warehouse->id,
                'order_date' => now()->subDays(2)->toDateString(),
                'payment_type' => 'tempo',
                'due_date' => now()->addDays(14)->toDateString(),
                'status' => 'completed',
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'shipping_cost' => 15000,
                'grand_total' => 0,
                'notes' => 'Penjualan demo untuk pelanggan utama',
                'source' => 'sales',
                'created_by' => $salesUser->id,
                'approved_by' => $salesUser->id,
            ]
        );

        $products = Product::whereIn('sku', ['PRD-0001', 'PRD-0003', 'PRD-0007'])->get();
        $subtotal = 0;
        $lineItems = [];

        foreach ($products as $product) {
            $qty = $product->sku === 'PRD-0003' ? 2 : 3;
            $price = $product->sell_price;
            $lineTotal = $qty * $price;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'unit' => $product->unit,
                'price' => $price,
                'discount' => 0,
                'subtotal' => $lineTotal,
                'notes' => 'Item penjualan demo',
            ];
        }

        foreach ($lineItems as $lineItem) {
            SalesOrderItem::firstOrCreate(
                [
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $lineItem['product_id'],
                ],
                $lineItem
            );
        }

        $tax = round($subtotal * 0.11, 2);
        $grandTotal = $subtotal + $tax + 15000;
        $salesOrder->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'grand_total' => $grandTotal,
        ]);

        $invoice = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-20260807-001'],
            [
                'sales_order_id' => $salesOrder->id,
                'customer_id' => $customer->id,
                'invoice_date' => now()->subDays(2)->toDateString(),
                'due_date' => now()->addDays(12)->toDateString(),
                'status' => 'partial',
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'paid_amount' => 5000000,
                'remaining_amount' => $grandTotal - 5000000,
                'notes' => 'Invoice demo',
                'created_by' => $salesUser->id,
            ]
        );

        foreach ($lineItems as $lineItem) {
            InvoiceItem::firstOrCreate(
                [
                    'invoice_id' => $invoice->id,
                    'product_id' => $lineItem['product_id'],
                ],
                [
                    'qty' => $lineItem['qty'],
                    'unit' => $lineItem['unit'],
                    'price' => $lineItem['price'],
                    'discount' => $lineItem['discount'],
                    'subtotal' => $lineItem['subtotal'],
                ]
            );
        }

        $receivable = Receivable::firstOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'customer_id' => $customer->id,
                'amount' => $grandTotal,
                'paid_amount' => 5000000,
                'remaining_amount' => $grandTotal - 5000000,
                'due_date' => now()->addDays(12)->toDateString(),
                'status' => 'partial',
                'notes' => 'Piutang penjualan demo',
            ]
        );

        Payment::firstOrCreate(
            ['payment_number' => 'PAY-20260807-001'],
            [
                'receivable_id' => $receivable->id,
                'customer_id' => $customer->id,
                'payment_date' => now()->subDay()->toDateString(),
                'amount' => 5000000,
                'payment_method' => 'transfer',
                'reference_number' => 'TRF-001',
                'bank_name' => 'Bank BCA',
                'notes' => 'Pembayaran awal',
                'received_by' => $salesUser->id,
            ]
        );

        $delivery = Delivery::firstOrCreate(
            ['delivery_number' => 'SJ-20260807-001'],
            [
                'sales_order_id' => $salesOrder->id,
                'driver_id' => 1,
                'vehicle_id' => 1,
                'delivery_date' => now()->subDay()->toDateString(),
                'status' => 'delivered',
                'received_by' => 'Toko Makmur Jaya',
                'received_at' => now(),
                'notes' => 'Pengiriman selesai',
                'created_by' => $salesUser->id,
            ]
        );

        foreach ($lineItems as $lineItem) {
            DeliveryItem::firstOrCreate(
                [
                    'delivery_id' => $delivery->id,
                    'product_id' => $lineItem['product_id'],
                ],
                [
                    'sales_order_item_id' => $salesOrder->items()->where('product_id', $lineItem['product_id'])->value('id'),
                    'qty' => $lineItem['qty'],
                ]
            );
        }

        foreach ($lineItems as $lineItem) {
            $this->adjustStock(
                Product::find($lineItem['product_id']),
                $warehouse,
                $lineItem['qty'],
                'out',
                'sales_order',
                $salesOrder->id,
                'Pengurangan stok dari penjualan'
            );
        }
    }

    private function seedPromos(): void
    {
        $promo1 = Promo::firstOrCreate(
            ['slug' => 'promo-paket-ramadhan'],
            [
                'title' => 'Promo Paket Ramadhan',
                'slug' => 'promo-paket-ramadhan',
                'description' => 'Diskon khusus untuk pembelian beras dan minyak',
                'type' => 'discount_percent',
                'value' => 10,
                'min_purchase' => 500000,
                'max_discount' => 50000,
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(15)->toDateString(),
                'is_active' => true,
            ]
        );

        $promo2 = Promo::firstOrCreate(
            ['slug' => 'promo-mie-khusus'],
            [
                'title' => 'Promo Mie Khusus',
                'slug' => 'promo-mie-khusus',
                'description' => 'Beli 2 gratis 1 untuk mie instan',
                'type' => 'buy_x_get_y',
                'value' => 1,
                'min_purchase' => 200000,
                'max_discount' => 0,
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addDays(20)->toDateString(),
                'is_active' => true,
            ]
        );

        $productsPromo1 = Product::whereIn('sku', ['PRD-0001', 'PRD-0003'])->pluck('id');
        $productsPromo2 = Product::whereIn('sku', ['PRD-0007', 'PRD-0008'])->pluck('id');

        DB::table('promo_products')->where('promo_id', $promo1->id)->delete();
        DB::table('promo_products')->insert(array_map(function ($productId) use ($promo1) {
            return ['promo_id' => $promo1->id, 'product_id' => $productId, 'created_at' => now(), 'updated_at' => now()];
        }, $productsPromo1->all()));

        DB::table('promo_products')->where('promo_id', $promo2->id)->delete();
        DB::table('promo_products')->insert(array_map(function ($productId) use ($promo2) {
            return ['promo_id' => $promo2->id, 'product_id' => $productId, 'created_at' => now(), 'updated_at' => now()];
        }, $productsPromo2->all()));
    }

    private function adjustStock(Product $product, Warehouse $warehouse, int $qty, string $type, string $referenceType, int $referenceId, string $notes): void
    {
        $current = DB::table('product_warehouse')->where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->first();
        $stockBefore = $current ? (int) $current->stock : 0;

        if ($type === 'out') {
            $stockAfter = max(0, $stockBefore - $qty);
            DB::table('product_warehouse')->updateOrInsert(
                ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                ['stock' => $stockAfter, 'min_stock' => $product->min_stock, 'updated_at' => now()]
            );
        } else {
            $stockAfter = $stockBefore + $qty;
            DB::table('product_warehouse')->updateOrInsert(
                ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                ['stock' => $stockAfter, 'min_stock' => $product->min_stock, 'updated_at' => now()]
            );
        }

        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'qty' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'notes' => $notes,
            'created_by' => User::where('email', 'gudang@sinarwardana.com')->value('id'),
        ]);
    }
}
