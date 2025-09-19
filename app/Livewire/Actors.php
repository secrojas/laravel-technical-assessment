<?php

namespace App\Livewire;

use App\Services\ActorService;
use Livewire\WithPagination;
use Livewire\Component;

class Actors extends Component
{
    use WithPagination;

    public $search = '';

    protected ActorService $actorService;

    public function boot(ActorService $actorService)
    {
        $this->actorService = $actorService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(ActorService $actorService)
    {;
        $actors = $this->actorService->listActorsWithMovies(9, $this->search);

        return view('livewire.settings.actors', compact('actors'));
    }
}
