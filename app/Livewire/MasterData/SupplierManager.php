<?php

namespace App\Livewire\MasterData;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    public $showModal = false;
    public $isEdit = false;
    public $supplierId;
    
    // Form fields
    public $name, $code, $pic, $phone, $email, $npwp, $address, $city, $province, $postal_code, $notes;
    public $is_active = true;

    protected $listeners = ['deleteConfirm' => 'delete'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'npwp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
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
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->code = $supplier->code;
        $this->pic = $supplier->pic;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->npwp = $supplier->npwp;
        $this->address = $supplier->address;
        $this->city = $supplier->city;
        $this->province = $supplier->province;
        $this->postal_code = $supplier->postal_code;
        $this->notes = $supplier->notes;
        $this->is_active = $supplier->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEdit) {
            $supplier = Supplier::findOrFail($this->supplierId);
            $supplier->update($validatedData);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Supplier berhasil diperbarui.']);
        } else {
            Supplier::create($validatedData);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Supplier berhasil ditambahkan.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Supplier ini akan dihapus.',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Supplier berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['name', 'code', 'pic', 'phone', 'email', 'npwp', 'address', 'city', 'province', 'postal_code', 'notes', 'is_active', 'supplierId']);
        $this->resetValidation();
    }

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.master-data.supplier-manager', [
            'suppliers' => $suppliers
        ])->layout('layouts.app');
    }
}
