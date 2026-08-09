<?php

namespace App\Livewire\MasterData;

use App\Models\Warehouse;
use App\Enums\WarehouseType;
use Livewire\Component;
use Livewire\WithPagination;

class WarehouseManager extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    public $showModal = false;
    public $isEdit = false;
    public $warehouseId;
    
    // Form fields
    public $name, $code, $type, $address, $phone, $pic;
    public $is_active = true;

    protected $listeners = ['deleteConfirm' => 'delete'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'pic' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $warehouse = Warehouse::findOrFail($id);
        $this->warehouseId = $warehouse->id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->type = $warehouse->type instanceof \BackedEnum ? $warehouse->type->value : $warehouse->type;
        $this->address = $warehouse->address;
        $this->phone = $warehouse->phone;
        $this->pic = $warehouse->pic;
        $this->is_active = $warehouse->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEdit) {
            $warehouse = Warehouse::findOrFail($this->warehouseId);
            $warehouse->update($validatedData);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Gudang berhasil diperbarui.']);
        } else {
            Warehouse::create($validatedData);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Gudang berhasil ditambahkan.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Gudang ini akan dihapus.',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        Warehouse::findOrFail($id)->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Gudang berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['name', 'code', 'type', 'address', 'phone', 'pic', 'is_active', 'warehouseId']);
        $this->resetValidation();
    }

    public function render()
    {
        $query = Warehouse::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('pic', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $warehouses = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        // Fetch enum cases safely if it's an enum, otherwise mock types
        $types = enum_exists(WarehouseType::class) ? WarehouseType::cases() : [];

        return view('livewire.master-data.warehouse-manager', [
            'warehouses' => $warehouses,
            'types' => $types
        ])->layout('layouts.app');
    }
}
