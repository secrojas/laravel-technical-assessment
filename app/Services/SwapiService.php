<?php

namespace App\Services;

use App\Repositories\ActorRepository;
use App\Repositories\MovieRepository;
use App\Services\Contracts\SwapiServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class SwapiService implements SwapiServiceInterface
{
    private const CACHE_TTL_PEOPLE_FILM = 604800;

    public function __construct(
        private ActorRepository $actorRepo,
        private MovieRepository $movieRepo
    ) {}

    public function searchPeople(string $query): array
    {
        if (empty($query)) {
            return [];
        }

        try {
            $people = $this->fetchPeople($query);

            return collect($people)->map(function ($person) {
                return $this->processActor($person);
            })->all();

        } catch (Exception $e) {
            Log::error("SWAPI search failed: {$e->getMessage()}");
            return [];
        }
    }

    private function fetchPeople(string $query): array
    {
        $cacheKey = 'swapi-search-' . strtolower(trim($query));

        return Cache::remember($cacheKey, self::CACHE_TTL_PEOPLE_FILM, function () use ($query) {
            $response = Http::get(config('swapi.base_url') . '/people', [
                'search' => $query,
            ]);

            if ($response->failed()) {
                throw new Exception("Failed to fetch people from SWAPI");
            }

            return $response->json('results') ?? [];
        });
    }

    private function processActor(array $person): array
    {
        $actor = $this->actorRepo->saveFromSwapi($person);

        if (!empty($person['films'])) {
            $person['films_details'] = collect($person['films'])->map(function ($filmUrl) use ($actor) {
                $filmData = $this->fetchFilm($filmUrl);

                if (!$filmData) {
                    return ['title' => 'Unknown'];
                }

                $movie = $this->movieRepo->saveFromSwapi($filmData);
                $movie->actors()->syncWithoutDetaching([$actor->id]);

                return [
                    'title'        => $filmData['title'] ?? 'Unknown',
                    'episode_id'   => $filmData['episode_id'] ?? null,
                    'release_date' => $filmData['release_date'] ?? null,
                    'director'     => $filmData['director'] ?? null,
                    'producer'     => $filmData['producer'] ?? null,
                ];
            })->all();
        }

        return $person;
    }

    private function fetchFilm(string $filmUrl): ?array
    {
        $filmKey = 'swapi-film-' . md5(strtolower(trim($filmUrl)));

        return Cache::remember($filmKey, self::CACHE_TTL_PEOPLE_FILM, function () use ($filmUrl) {
            $response = Http::get($filmUrl);

            if ($response->failed()) {
                Log::warning("Failed to fetch film from SWAPI: $filmUrl");
                return null;
            }

            return $response->json();
        });
    }
}