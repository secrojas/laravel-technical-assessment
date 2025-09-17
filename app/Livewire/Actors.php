<?php

namespace App\Livewire;

use App\Models\Actor;
use Livewire\WithPagination;
use Livewire\Component;

class Actors extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $actors = Actor::with('movies')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate(9);

        return view('livewire.actors', compact('actors'));
    }
}
