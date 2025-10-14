<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Position;

class Positions extends Component
{
    public $positions = [];
    public $name;
    public $description;
    public $is_active = true;
    public $editing_id;
    public $search = '';

    protected $rules = [
        'name' => 'required|unique:positions,name',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadPositions();
    }

    public function render()
    {
        return view('livewire.positions');
    }

    public function savePosition()
    {
        if ($this->editing_id) {
            $position = Position::find($this->editing_id);
            if ($position) {
                $this->validate([
                    'name' => 'required|unique:positions,name,' . $this->editing_id,
                    'description' => 'nullable',
                    'is_active' => 'boolean',
                ]);
                
                $position->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'is_active' => $this->is_active,
                ]);

                session()->flash('message', 'Jabatan berhasil diperbarui.');
            }
        } else {
            $validated = $this->validate();
            
            Position::create($validated);

            session()->flash('message', 'Jabatan berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadPositions();
    }

    public function editPosition($id)
    {
        $position = Position::find($id);
        if ($position) {
            $this->editing_id = $position->id;
            $this->name = $position->name;
            $this->description = $position->description;
            $this->is_active = $position->is_active;
        }
    }

    public function deletePosition($id)
    {
        $position = Position::find($id);
        if ($position) {
            $position->delete();
            session()->flash('message', 'Jabatan berhasil dihapus.');
            $this->loadPositions();
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->editing_id = null;
    }

    public function loadPositions()
    {
        $query = Position::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->positions = $query->orderBy('name')->get();
    }
}
