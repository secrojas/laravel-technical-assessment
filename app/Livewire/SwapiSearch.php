<?php

namespace App\Livewire;

use App\Services\Contracts\SwapiServiceInterface;
use App\Services\SwapiService;
use Livewire\Component;

class SwapiSearch extends Component
{
    public $query = '';
    public $results = [];

    protected SwapiService $swapiService;

    public function boot(SwapiServiceInterface $swapiService): void
    {
        $this->swapiService = $swapiService;
    }

    public function search()
    {
        $this->results = $this->swapiService->searchPeople($this->query);
    }

    public function render()
    {
        return view('livewire.swapi-search');
    }
}
