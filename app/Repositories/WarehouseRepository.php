<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Interfaces\WarehouseRepositoryInterface;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    public function all()
    {
        return Warehouse::orderBy('name')->get();
    }

    public function find($id)
    {
        return Warehouse::findOrFail($id);
    }

    public function create(array $data)
    {
        return Warehouse::create($data);
    }

    public function update($id, array $data)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($data);
        return $warehouse;
    }

    public function delete($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return $warehouse->delete();
    }

    public function getActive()
    {
        return Warehouse::active()->get();
    }

    public function getByType($type)
    {
        return Warehouse::byType($type)->get();
    }
}
