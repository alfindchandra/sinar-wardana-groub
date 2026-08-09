<?php

namespace App\Livewire\MasterData;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Enums\ProductUnit;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;
    public $isEdit = false;

    // Form fields
    public $barcode, $sku, $name, $brand, $category_id, $supplier_id, $unit, $weight, $content_per_unit, $description;
    public $min_purchase = 1, $base_cost = 0, $sell_price = 0, $distributor_price = 0, $agent_price = 0, $store_price = 0, $min_stock = 0;
    public $is_active = true;

    // Image upload
    public $newImages = [];

    public function mount(?Product $product = null)
    {
        if ($product && $product->exists) {
            $this->product = $product;
            $this->isEdit = true;

            $this->barcode = $product->barcode;
            $this->sku = $product->sku;
            $this->name = $product->name;
            $this->brand = $product->brand;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->unit = $product->unit;
            $this->weight = $product->weight;
            $this->content_per_unit = $product->content_per_unit;
            $this->description = $product->description;
            $this->min_purchase = $product->min_purchase;
            $this->base_cost = $product->base_cost;
            $this->sell_price = $product->sell_price;
            $this->distributor_price = $product->distributor_price;
            $this->agent_price = $product->agent_price;
            $this->store_price = $product->store_price;
            $this->min_stock = $product->min_stock;
            $this->is_active = $product->is_active;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit' => 'required|string',
            'base_cost' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'newImages.*' => 'image|max:2048', // 2MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'name' => $this->name,
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'unit' => $this->unit,
            'weight' => $this->weight,
            'content_per_unit' => $this->content_per_unit,
            'description' => $this->description,
            'min_purchase' => $this->min_purchase,
            'base_cost' => $this->base_cost,
            'sell_price' => $this->sell_price,
            'distributor_price' => $this->distributor_price,
            'agent_price' => $this->agent_price,
            'store_price' => $this->store_price,
            'min_stock' => $this->min_stock,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            $this->product->update($data);
            $product = $this->product;
            session()->flash('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Produk berhasil diperbarui.']);
        } else {
            $product = Product::create($data);
            session()->flash('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Produk berhasil ditambahkan.']);
        }

        // Handle image upload if there are any
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0 && $product->images()->count() === 0
                ]);
            }
        }

        return redirect()->route('master-data.products.index');
    }

    public function render()
    {
        $categories = Category::active()->get();
        $suppliers = Supplier::active()->get();
        $units = enum_exists(ProductUnit::class) ? ProductUnit::cases() : ['PCS', 'BOX', 'KG', 'LITER'];

        return view('livewire.master-data.product-form', [
            'categories' => $categories,
            'suppliers' => $suppliers,
            'units' => $units
        ])->layout('layouts.app');
    }
}
