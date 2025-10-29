<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FamilyComposition;

class FamilyCompositions extends Component
{
    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $isEditing = false;

    // Form fields
    public $number;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $delete_id;
    public $search = '';
    public $deletingFamilyCompositionInfo = '';

    // Data
    public $family_compositions = [];

    protected $rules = [
        'number' => 'required|integer|unique:family_compositions,number',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'number.required' => 'Nomor susunan keluarga wajib diisi.',
        'number.integer' => 'Nomor susunan keluarga harus berupa angka.',
        'number.unique' => 'Nomor susunan keluarga sudah terdaftar.',
    ];

    public function mount()
    {
        $this->loadFamilyCompositions();
    }

    public function render()
    {
        return view('livewire.family-compositions');
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
            $this->number = $familyComposition->number;
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
            $this->deletingFamilyCompositionInfo = "Susunan Keluarga {$familyComposition->number}";
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
                'number' => 'required|integer|unique:family_compositions,number,' . $this->editing_id,
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            $familyComposition = FamilyComposition::find($this->editing_id);
            if ($familyComposition) {
                $familyComposition->update([
                    'number' => $this->number,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Susunan keluarga berhasil diperbarui.');
                $this->closeModal();
            }
        } else {
            $this->validate([
                'number' => 'required|integer|unique:family_compositions,number',
                'description' => 'nullable',
                'is_active' => 'boolean',
            ]);

            FamilyComposition::create([
                'number' => $this->number,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            session()->flash('message', 'Susunan keluarga berhasil ditambahkan.');
            $this->closeModal();
        }

        $this->loadFamilyCompositions();
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
        $this->loadFamilyCompositions();
    }

    // Utility methods
    public function resetForm()
    {
        $this->number = 0;
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
        $this->isEditing = false;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->loadFamilyCompositions();
    }

    public function loadFamilyCompositions()
    {
        $query = FamilyComposition::query();

        if ($this->search) {
            $query->where('number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->family_compositions = $query->orderBy('number')->get();
    }
}
