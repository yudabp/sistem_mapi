<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FamilyComposition;

class FamilyCompositions extends Component
{
    public $family_compositions = [];
    public $number;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'number' => 'required|integer|unique:family_compositions,number',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadFamilyCompositions();
    }

    public function render()
    {
        return view('livewire.family-compositions');
    }

    public function saveFamilyComposition()
    {
        if ($this->editing_id) {
            $familyComposition = FamilyComposition::find($this->editing_id);
            if ($familyComposition) {
                $this->validate([
                    'number' => 'required|integer|unique:family_compositions,number,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $familyComposition->update([
                    'number' => $this->number,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Susunan keluarga berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            FamilyComposition::create($validated);

            session()->flash('message', 'Susunan keluarga berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadFamilyCompositions();
    }

    public function editFamilyComposition($id)
    {
        $familyComposition = FamilyComposition::find($id);
        if ($familyComposition) {
            $this->editing_id = $familyComposition->id;
            $this->number = $familyComposition->number;
            $this->description = $familyComposition->description;
            $this->is_active = $familyComposition->is_active;
        }
    }

    public function deleteFamilyComposition($id)
    {
        $familyComposition = FamilyComposition::find($id);
        if ($familyComposition) {
            $familyComposition->delete();
            session()->flash('message', 'Susunan keluarga berhasil dihapus.');
            $this->loadFamilyCompositions();
        }
    }

    public function resetForm()
    {
        $this->number = 0;
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
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
