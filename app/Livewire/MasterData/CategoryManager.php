<?php

namespace App\Livewire\MasterData;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    
    public $showModal = false;
    public $isEdit = false;
    public $categoryId;
    
    // Form fields
    public $name = '';
    public $description = '';
    public $icon = '';         // Menyimpan path SVG atau class FontAwesome
    public $svg_file;          // Temporary file upload untuk SVG
    public $sort_order = 0;
    public $is_active = true;

    protected $listeners = ['deleteConfirm' => 'delete'];

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->ignore($this->categoryId),
            ],
            'description' => 'nullable|string',
            'svg_file' => 'nullable|file|mimes:svg,xml|max:1024', // Maks 1MB
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected function messages()
    {
        return [
            'name.unique' => 'Nama kategori ini sudah dipakai. Gunakan nama lain.',
            'svg_file.mimes' => 'File harus berformat SVG.',
            'svg_file.max' => 'Ukuran file SVG maksimal 1MB.',
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
        $this->is_active = (bool) $category->is_active;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $iconPath = $this->icon;

        // Proses upload file SVG jika ada file baru diunggah
        if ($this->svg_file) {
            // Hapus file SVG lama jika sedang mengedit
            if ($this->isEdit && $this->icon && str_starts_with($this->icon, 'categories/')) {
                Storage::disk('public')->delete($this->icon);
            }

            // Simpan SVG baru ke storage/app/public/categories
            $iconPath = $this->svg_file->store('categories', 'public');
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $iconPath,
            'sort_order' => (int) $this->sort_order,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->isEdit) {
                $category = Category::findOrFail($this->categoryId);
                $category->update($data);
                $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil diperbarui.']);
            } else {
                $data['slug'] = $this->uniqueSlug($this->name);
                Category::create($data);
                $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil ditambahkan.']);
            }
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            $this->addError('name', 'Nama kategori ini sudah dipakai. Gunakan nama lain.');
            return;
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (Category::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function removeIcon()
    {
        if ($this->icon && str_starts_with($this->icon, 'categories/')) {
            Storage::disk('public')->delete($this->icon);
        }
        $this->icon = null;
        $this->svg_file = null;
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
        $category = Category::findOrFail($id);
        
        // Hapus file SVG dari storage jika ada
        if ($category->icon && str_starts_with($category->icon, 'categories/')) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();
        $this->dispatch('toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Kategori berhasil dihapus.']);
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'icon', 'svg_file', 'sort_order', 'is_active', 'categoryId']);
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