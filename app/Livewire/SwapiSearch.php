<?php

namespace App\Livewire;

use App\Models\Actor;
use App\Models\Movie;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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

        $cacheKey = 'swapi-search-' . strtolower(trim($this->query));
        
        $people = Cache::remember($cacheKey, 3600, function () {
            $response = Http::get(config('swapi.base_url') . '/people', [
                'search' => $this->query,
            ]);

            return $response->json()['results'] ?? [];
        });

        foreach ($people as &$person) {

            $actor = Actor::updateOrCreate(
                ['swapi_url' => $person['url']],
                [
                    'name'       => $person['name'],
                    'birthdate'  => $person['birth_year'] ?? null,
                    'gender'     => $person['gender'] ?? null,
                    'height'     => $person['height'] ?? null,
                    'mass'       => $person['mass'] ?? null,
                    'hair_color' => $person['hair_color'] ?? null,
                    'skin_color' => $person['skin_color'] ?? null,
                    'eye_color'  => $person['eye_color'] ?? null,
                ]
            );

            if (!empty($person['films'])) {
                foreach ($person['films'] as $filmUrl) {
                    $filmKey = 'swapi-film-' . md5(strtolower(trim($filmUrl)));

                    $filmData = Cache::remember($filmKey, 86400, function () use ($filmUrl) {
                        return Http::get($filmUrl)->json();
                    });

                    Movie::updateOrCreate(
                        [
                            'actor_id' => $actor->id,
                            'title'    => $filmData['title'] ?? 'Unknown',
                        ],
                        [
                            'year' => isset($filmData['release_date'])
                                ? Carbon::parse($filmData['release_date'])->format('Y')
                                : null,
                        ]
                    );
                }

                $person['films_details'] = collect($person['films'])->map(function ($filmUrl) {
                    $film = Cache::get('swapi-film-' . md5(strtolower(trim($filmUrl))));
                    return [
                        'title'        => $film['title'] ?? 'Unknown',
                        'release_date' => $film['release_date'] ?? 'Unknown',
                    ];
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
