<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductService
{
    protected ImageManager $imageManager;

    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function list(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function find(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    /**
     * @param  array  $data  Field produk (name, sku, sell_price, content_per_bal, pcs_per_bal, dst)
     * @param  UploadedFile[]  $images  File gambar baru yang diupload
     * @param  array  $warehouseStocks  [['warehouse_id' => 1, 'stock' => 10, 'min_stock' => 5], ...]
     * @param  array  $variants  [['name' => 'Ungu', 'extra_price' => 0, 'stock' => 10, 'is_active' => true], ...]
     */
    public function create(array $data, array $images = [], array $warehouseStocks = [], array $variants = []): Product
    {
        return DB::transaction(function () use ($data, $images, $warehouseStocks, $variants) {
            if (empty($data['sku'])) {
                $data['sku'] = $this->generateSku();
            }

            $product = $this->repository->create($data);

            $this->syncImages($product, $images);
            $this->syncWarehouseStocks($product, $warehouseStocks);
            $this->syncVariants($product, $variants);

            return $product->fresh(['category', 'supplier', 'images', 'variants', 'warehouses']);
        });
    }

    /**
     * @param  UploadedFile[]  $newImages
     * @param  int[]  $removedImageIds
     */
    public function update(Product $product, array $data, array $newImages = [], array $removedImageIds = [], array $warehouseStocks = [], array $variants = []): Product
    {
        return DB::transaction(function () use ($product, $data, $newImages, $removedImageIds, $warehouseStocks, $variants) {
            $this->repository->update($product, $data);

            foreach ($removedImageIds as $imageId) {
                $this->deleteImage($product, (int) $imageId);
            }

            $this->syncImages($product, $newImages);

            if (! empty($warehouseStocks)) {
                $this->syncWarehouseStocks($product, $warehouseStocks, mergeOnly: true);
            }

            $this->syncVariants($product, $variants);

            return $product->fresh(['category', 'supplier', 'images', 'variants', 'warehouses']);
        });
    }

    public function delete(Product $product): bool
    {
        if ($product->stockMovements()->exists()) {
            throw ValidationException::withMessages([
                'product' => 'Produk tidak bisa dihapus karena sudah memiliki riwayat mutasi stok.',
            ]);
        }

        return DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            return $this->repository->delete($product);
        });
    }

    public function toggleStatus(Product $product): Product
    {
        $product->update(['is_active' => ! $product->is_active]);

        return $product;
    }

    public function setPrimaryImage(Product $product, int $imageId): void
    {
        $product->images()->update(['is_primary' => false]);
        $product->images()->where('id', $imageId)->update(['is_primary' => true]);
    }

    public function deleteImage(Product $product, int $imageId): void
    {
        $image = $product->images()->find($imageId);

        if (! $image) {
            return;
        }

        Storage::disk('public')->delete($image->image_path);
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }
    }

    /**
     * @param  UploadedFile[]  $images
     */
    protected function syncImages(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = (int) $product->images()->max('sort_order') + 1;

        foreach ($images as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeResizedImage($file);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $nextSort + $index,
            ]);

            if (! $hasPrimary && $index === 0) {
                $hasPrimary = true;
            }
        }
    }

    protected function storeResizedImage(UploadedFile $file): string
    {
        $filename = 'products/' . date('Y/m') . '/' . Str::uuid() . '.webp';

        $image = $this->imageManager->read($file->getRealPath())
            ->cover(800, 800)
            ->toWebp(85);

        Storage::disk('public')->put($filename, (string) $image);

        return $filename;
    }

    /**
     * @param  array<array{warehouse_id:int, stock:int, min_stock:int}>  $warehouseStocks
     */
    protected function syncWarehouseStocks(Product $product, array $warehouseStocks, bool $mergeOnly = false): void
    {
        if (empty($warehouseStocks)) {
            return;
        }

        $sync = [];
        foreach ($warehouseStocks as $row) {
            if (empty($row['warehouse_id'])) {
                continue;
            }

            // Saat edit (mergeOnly), jangan menimpa stok yang sudah berjalan lewat modul Gudang/Stok —
            // hanya perbarui min_stock supaya alur stok tetap dikelola lewat mutasi barang masuk/keluar.
            if ($mergeOnly && $product->warehouses()->where('warehouse_id', $row['warehouse_id'])->exists()) {
                $product->warehouses()->updateExistingPivot($row['warehouse_id'], [
                    'min_stock' => (int) ($row['min_stock'] ?? 0),
                ]);

                continue;
            }

            $sync[$row['warehouse_id']] = [
                'stock' => (int) ($row['stock'] ?? 0),
                'min_stock' => (int) ($row['min_stock'] ?? 0),
            ];
        }

        if (! empty($sync)) {
            $product->warehouses()->syncWithoutDetaching($sync);
        }
    }

    /**
     * Sinkronisasi varian wajib pilih (Warna/Rasa). Varian yang tidak lagi ada di form akan dihapus,
     * yang sudah ada di-update, yang baru dibuat.
     *
     * @param  array<array{id:?int, name:string, extra_price:float, stock:int, is_active:bool}>  $variants
     */
    protected function syncVariants(Product $product, array $variants): void
    {
        // Kosongkan seluruhnya hanya jika form memang tidak mengirim variant apa pun sejak awal
        // (bukan karena user menghapus semuanya secara sengaja — form selalu kirim array, walau kosong).
        $keepIds = [];

        foreach ($variants as $variant) {
            if (empty($variant['name'])) {
                continue;
            }

            $row = ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                ],
                [
                    'extra_price' => $variant['extra_price'] ?? 0,
                    'stock' => $variant['stock'] ?? 0,
                    'is_active' => $variant['is_active'] ?? true,
                    'sort_order' => $variant['sort_order'] ?? 0,
                ]
            );

            $keepIds[] = $row->id;
        }

        $product->variants()->whereNotIn('id', $keepIds)->delete();
    }

    protected function generateSku(): string
    {
        $latest = Product::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;

        return 'PRD-' . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }
}
