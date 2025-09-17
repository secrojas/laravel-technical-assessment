<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SwapiSearch extends Component
{
    public $query = '';
    public $results = [];

    public function search()
    {
        if (empty($this->query)) {
            $this->results = [];
            return;
        }

        $people = Cache::remember(
            "swapi-search-{$this->query}",
            3600,
            function () {
                $response = Http::get('https://swapi.dev/api/people', [
                    'search' => $this->query,
                ]);

                return $response->json()['results'] ?? [];
            }
        );

        // Fetch film details for each person
        foreach ($people as &$person) {
            if (!empty($person['films'])) {
                $person['films_details'] = collect($person['films'])->map(function ($filmUrl) {
                    return Cache::remember("swapi-film-{$filmUrl}", 86400, function () use ($filmUrl) {
                        $response = Http::get($filmUrl);
                        return [
                            'title' => $response->json()['title'] ?? 'Unknown',
                            'release_date' => $response->json()['release_date'] ?? 'Unknown',
                        ];
                    });
                });
            }
        }

        $this->results = $people;
    }

    public function render()
    {
        return view('livewire.swapi-search');
    }
}
