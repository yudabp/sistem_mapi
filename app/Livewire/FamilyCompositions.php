<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FamilyComposition;

class FamilyCompositions extends Component
{
    use WithPagination;
    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $isEditing = false;

    // Form fields
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $delete_id;
    public $search = '';
    public $deletingFamilyCompositionInfo = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|string|max:255|unique:family_compositions,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama susunan keluarga wajib diisi.',
        'name.string' => 'Nama susunan keluarga harus berupa teks.',
        'name.max' => 'Nama susunan keluarga tidak boleh lebih dari 255 karakter.',
        'name.unique' => 'Nama susunan keluarga sudah terdaftar.',
    ];

    public function mount()
    {
        // Initialize any required data
    }

    public function render()
    {
        $query = FamilyComposition::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $family_compositions = $query->orderBy('name')->paginate($this->perPage);

        return view('livewire.family-compositions', [
            'family_compositions' => $family_compositions,
        ]);
    }

    // Modal methods
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $familyComposition = FamilyComposition::find($id);
        if ($familyComposition) {
            $this->editing_id = $familyComposition->id;
            $this->name = $familyComposition->name;
            $this->description = $familyComposition->description;
            $this->is_active = $familyComposition->is_active;
            $this->isEditing = true;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $familyComposition = FamilyComposition::find($id);
        if ($familyComposition) {
            $this->delete_id = $id;
            $this->deletingFamilyCompositionInfo = "Susunan Keluarga {$familyComposition->name}";
            $this->showDeleteConfirmation = true;
        }
    }

    public function closeDeleteConfirmation()
    {
        $this->showDeleteConfirmation = false;
        $this->delete_id = null;
        $this->deletingFamilyCompositionInfo = '';
    }

    // CRUD operations
    public function saveFamilyComposition()
    {
        if ($this->isEditing) {
            $this->validate([
                'name' => 'required|string|max:255|unique:family_compositions,name,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $familyComposition = FamilyComposition::find($this->editing_id);
            if ($familyComposition) {
                $familyComposition->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Susunan keluarga berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'name' => 'required|string|max:255|unique:family_compositions,name',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            FamilyComposition::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Susunan keluarga berhasil ditambahkan.');
            $this->closeModal();
        }

        }

    public function deleteFamilyComposition()
    {
        if ($this->delete_id) {
            $familyComposition = FamilyComposition::find($this->delete_id);
            if ($familyComposition) {
                $familyComposition->delete();
                session()->flash('message', 'Susunan keluarga berhasil dihapus.');
            }
        }

        $this->closeDeleteConfirmation();
    }

    // Utility methods
    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
        $this->isEditing = false;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

}
