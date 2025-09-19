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
    ) {
    }

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
            $person['films_details'] = $this->fetchFilmsInParallel($person['films'], $actor->id);
        }

        return $person;
    }

    /**
     * Fetch multiple films in parallel using Http::pool()
     */
    private function fetchFilmsInParallel(array $filmUrls, int $actorId): array
    {
        $toFetch = [];
        $cached = [];

        // Check cache first
        foreach ($filmUrls as $url) {
            $filmKey = $this->filmCacheKey($url);
            if (Cache::has($filmKey)) {
                $cached[$url] = Cache::get($filmKey);
            } else {
                $toFetch[$url] = $url;
            }
        }

        // Fetch missing films in parallel
        $responses = [];
        if (!empty($toFetch)) {
            $responses = Http::pool(function ($pool) use ($toFetch) {
                foreach ($toFetch as $url) {
                    $pool->as($url)->get($url);
                }
            });
        }

        $films = [];
        foreach ($filmUrls as $url) {
            $filmData = $cached[$url] ?? null;

            if (!$filmData && isset($responses[$url])) {
                $response = $responses[$url];
                if ($response->failed()) {
                    Log::warning("Failed to fetch film from SWAPI: $url");
                    continue;
                }

                $filmData = $response->json();
                Cache::put($this->filmCacheKey($url), $filmData, self::CACHE_TTL_PEOPLE_FILM);
            }

            if ($filmData) {
                $movie = $this->movieRepo->saveFromSwapi($filmData);
                $movie->actors()->syncWithoutDetaching([$actorId]);

                $films[] = [
                    'title'        => $filmData['title'] ?? 'Unknown',
                    'episode_id'   => $filmData['episode_id'] ?? null,
                    'release_date' => $filmData['release_date'] ?? null,
                    'director'     => $filmData['director'] ?? null,
                    'producer'     => $filmData['producer'] ?? null,
                ];
            }
        }

        return $films;
    }

    private function filmCacheKey(string $url): string
    {
        return 'swapi-film-' . md5(strtolower(trim($url)));
    }
}
