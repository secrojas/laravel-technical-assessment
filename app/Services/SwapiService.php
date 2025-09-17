<?php

namespace App\Services;

use App\Repositories\ActorRepository;
use App\Repositories\MovieRepository;
use App\Services\Contracts\SwapiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SwapiService implements SwapiServiceInterface
{
    public function __construct(
        private ActorRepository $actorRepo,
        private MovieRepository $movieRepo
    ) {}

    public function searchPeople(string $query): array
    {
        if (empty($query)) {
            return [];
        }

        $cacheKey = 'swapi-search-' . strtolower(trim($query));

        $people = Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::get(config('swapi.base_url') . '/people', [
                'search' => $query,
            ]);
            return $response->json()['results'] ?? [];
        });

        foreach ($people as &$person) {
            $actor = $this->actorRepo->saveFromSwapi($person);

            if (!empty($person['films'])) {
                foreach ($person['films'] as $filmUrl) {
                    $filmKey = 'swapi-film-' . md5(strtolower(trim($filmUrl)));

                    $filmData = Cache::remember($filmKey, 86400, function () use ($filmUrl) {
                        return Http::get($filmUrl)->json();
                    });

                    $this->movieRepo->saveForActor($actor->id, $filmData);
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

        return $people;
    }
}