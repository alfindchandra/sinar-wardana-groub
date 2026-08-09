<?php

namespace App\Livewire\MasterData;

use App\Enums\ProductUnit;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Form Produk')]
class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;
    public bool $isEdit = false;

    // Data utama
    public ?string $barcode = null;
    public ?string $sku = null;
    public string $name = '';
    public ?string $brand = null;
    public ?int $category_id = null;
    public ?int $supplier_id = null;
    public string $unit = 'dus';
    public ?float $weight = null;
    public ?string $description = null;
    public int $min_purchase = 1;
    public float $base_cost = 0;
    public float $sell_price = 0;
    public int $min_stock = 0;
    public bool $is_active = true;

    // Breakdown harga otomatis (Sak -> Bal -> Pcs), untuk tampilan/deskripsi saja
    public ?int $content_per_bal = null;
    public ?int $pcs_per_bal = null;

    // Gambar
    public array $newImages = [];
    public $existingImages = [];

    // Varian wajib pilih (Warna/Rasa) — opsional per produk
    public array $variants = [];

    // Stok awal per gudang (hanya diisi saat create)
    public array $warehouseStocks = [];

    protected ProductService $service;

    public function boot(ProductService $service): void
    {
        $this->service = $service;
    }

    public function mount(?Product $product = null): void
    {
        if ($product && $product->exists) {
            $this->authorize('update', $product);
            $this->isEdit = true;
            $this->product = $product;
            $this->fillFromProduct($product);
        } else {
            $this->authorize('create', Product::class);
            $this->initWarehouseStocks();
        }
    }

    protected function fillFromProduct(Product $product): void
    {
        $this->barcode = $product->barcode;
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->brand = $product->brand;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->unit = $product->unit;
        $this->weight = $product->weight ? (float) $product->weight : null;
        $this->content_per_bal = $product->content_per_bal;
        $this->pcs_per_bal = $product->pcs_per_bal;
        $this->description = $product->description;
        $this->min_purchase = $product->min_purchase;
        $this->base_cost = (float) $product->base_cost;
        $this->sell_price = (float) $product->sell_price;
        $this->min_stock = $product->min_stock;
        $this->is_active = $product->is_active;
        $this->existingImages = $product->images()->orderBy('sort_order')->get();

        $this->variants = $product->variants()->ordered()->get()
            ->map(fn ($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'extra_price' => (float) $v->extra_price,
                'stock' => $v->stock,
                'is_active' => $v->is_active,
            ])->toArray();

        $existingPivot = $product->warehouses()->get()->keyBy('id');
        $this->warehouseStocks = Warehouse::active()->orderBy('name')->get()->map(function ($wh) use ($existingPivot) {
            $pivot = $existingPivot->get($wh->id);

            return [
                'warehouse_id' => $wh->id,
                'warehouse_name' => $wh->name,
                'stock' => $pivot?->pivot->stock ?? 0,
                'min_stock' => $pivot?->pivot->min_stock ?? 0,
                'has_stock' => (bool) $pivot,
            ];
        })->toArray();
    }

    protected function initWarehouseStocks(): void
    {
        $this->warehouseStocks = Warehouse::active()->orderBy('name')->get()->map(fn ($wh) => [
            'warehouse_id' => $wh->id,
            'warehouse_name' => $wh->name,
            'stock' => 0,
            'min_stock' => 0,
            'has_stock' => false,
        ])->toArray();
    }

    public function addVariant(): void
    {
        $this->variants[] = [
            'id' => null,
            'name' => '',
            'extra_price' => 0,
            'stock' => 0,
            'is_active' => true,
        ];
    }

    public function removeVariant(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function removeNewImage(int $index): void
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    public function setPrimaryImage(int $imageId): void
    {
        if (! $this->product) {
            return;
        }

        $this->service->setPrimaryImage($this->product, $imageId);
        $this->existingImages = $this->product->images()->orderBy('sort_order')->get();
        $this->dispatch('toast', type: 'success', message: 'Gambar utama diperbarui.');
    }

    public function deleteExistingImage(int $imageId): void
    {
        if (! $this->product) {
            return;
        }

        $this->service->deleteImage($this->product, $imageId);
        $this->existingImages = $this->product->images()->orderBy('sort_order')->get();
        $this->dispatch('toast', type: 'success', message: 'Gambar dihapus.');
    }

    protected function rules(): array
    {
        return ProductRequest::rulesFor($this->product?->id);
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nama produk',
            'category_id' => 'kategori',
            'sell_price' => 'Harga Jual (Umum)',
            'content_per_bal' => 'isi Bal',
            'pcs_per_bal' => 'isi Pcs per Bal',
        ];
    }

    /**
     * Preview breakdown harga live saat admin mengetik (dipakai di tab Harga).
     */
    public function getBreakdownPreviewProperty(): ?string
    {
        if (! $this->content_per_bal || $this->content_per_bal <= 0 || $this->sell_price <= 0) {
            return null;
        }

        $balPrice = $this->sell_price / $this->content_per_bal;
        $unitLabel = ProductUnit::from($this->unit)->label();

        $text = sprintf(
            'Isi 1 %s = %d Bal (Rp %s/bal)',
            $unitLabel,
            $this->content_per_bal,
            number_format($balPrice, 0, ',', '.')
        );

        if ($this->pcs_per_bal && $this->pcs_per_bal > 0) {
            $pcsPrice = $balPrice / $this->pcs_per_bal;
            $totalPcs = $this->content_per_bal * $this->pcs_per_bal;
            $text .= sprintf(' | Total %d Pcs (Rp %s/pcs)', $totalPcs, number_format($pcsPrice, 0, ',', '.'));
        }

        return $text;
    }

    public function save()
    {
        $data = $this->validate();

        $this->validate([
            'newImages.*' => ['nullable', 'image', 'max:3072'],
            'variants.*.name' => ['nullable', 'string', 'max:100'],
            'variants.*.extra_price' => ['nullable', 'numeric'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ]);

        $variants = collect($this->variants)
            ->filter(fn ($v) => ! empty($v['name']))
            ->values()
            ->toArray();

        if ($this->isEdit) {
            $this->service->update(
                $this->product,
                $data,
                newImages: $this->newImages,
                warehouseStocks: $this->warehouseStocks,
                variants: $variants,
            );
            $message = 'Produk berhasil diperbarui.';
        } else {
            $activeStocks = collect($this->warehouseStocks)
                ->filter(fn ($w) => ($w['stock'] ?? 0) > 0 || ($w['min_stock'] ?? 0) > 0)
                ->toArray();

            $this->service->create(
                $data,
                images: $this->newImages,
                warehouseStocks: $activeStocks,
                variants: $variants,
            );
            $message = 'Produk baru berhasil ditambahkan.';
        }

        session()->flash('toast', ['type' => 'success', 'message' => $message, 'title' => 'Berhasil']);

        return $this->redirect(route('master-data.products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.master-data.product-form', [
            'categories' => Category::active()->ordered()->get(),
            'suppliers' => Supplier::active()->orderBy('name')->get(),
            'units' => ProductUnit::cases(),
        ]);
    }
}
