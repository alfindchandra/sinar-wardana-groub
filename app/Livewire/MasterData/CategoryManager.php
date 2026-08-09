<?php

namespace App\Livewire\MasterData;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    
    public $showModal = false;
    public $isEdit = false;
    public $categoryId;
    
    // Form fields
    public $name = '';
    public $description = '';
    public $icon = '';
    public $sort_order = 0;
    public $is_active = true;

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer',
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
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->sort_order = $category->sort_order;
        $this->is_active = $category->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'sort_order' => (int) $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($data);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil diperbarui.']);
        } else {
            $data['slug'] = Str::slug($this->name);
            Category::create($data);
            $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil ditambahkan.']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function triggerDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda yakin?',
            'text' => 'Kategori ini akan dihapus.',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'icon', 'sort_order', 'is_active', 'categoryId']);
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::withCount('products')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.master-data.category-manager', [
            'categories' => $categories
        ])->layout('layouts.app');
    }
}
