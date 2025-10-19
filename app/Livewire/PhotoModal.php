<?php

namespace App\Livewire;

use Livewire\Component;

class PhotoModal extends Component
{
    public $show = false;
    public $photoUrl = '';
    public $title = '';
    public $loading = false;

    protected $listeners = ['showPhoto' => 'openModal'];

    public function openModal($data)
    {
        $this->photoUrl = $data['url'] ?? '';
        $this->title = $data['title'] ?? 'Photo';
        $this->show = true;
        $this->loading = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->photoUrl = '';
        $this->title = '';
        $this->loading = false;
    }

    public function onPhotoLoad()
    {
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.photo-modal');
    }
}