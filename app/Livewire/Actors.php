<?php

namespace App\Livewire;

use App\Models\Actor;
use Livewire\Component;

class Actors extends Component
{
    public $search = '';

    public function render()
    {
        $actors = Actor::with('movies')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->get();

        return view('livewire.actors', compact('actors'));
    }
}
