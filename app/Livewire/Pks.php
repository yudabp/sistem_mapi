<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pks as PksModel;

class Pks extends Component
{
    public $pks_list = [];
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:pks,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadPks();
    }

    public function render()
    {
        return view('livewire.pks');
    }

    public function savePks()
    {
        if ($this->editing_id) {
            $pks = PksModel::find($this->editing_id);
            if ($pks) {
                $this->validate([
                    'name' => 'required|unique:pks,name,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $pks->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'PKS berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            PksModel::create($validated);

            session()->flash('message', 'PKS berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadPks();
    }

    public function editPks($id)
    {
        $pks = PksModel::find($id);
        if ($pks) {
            $this->editing_id = $pks->id;
            $this->name = $pks->name;
            $this->description = $pks->description;
            $this->is_active = $pks->is_active;
        }
    }

    public function deletePks($id)
    {
        $pks = PksModel::find($id);
        if ($pks) {
            $pks->delete();
            session()->flash('message', 'PKS berhasil dihapus.');
            $this->loadPks();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
    }

    public function loadPks()
    {
        $query = PksModel::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->pks_list = $query->orderBy('name')->get();
    }
}
