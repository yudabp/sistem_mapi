<?php

namespace App\Livewire;

use Livewire\Component;

class Settings extends Component
{
    public $activeTab = 'vehicle-numbers';

    public function render()
    {
        return view('livewire.settings');
    }
    
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }
}
