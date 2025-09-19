<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function saveForActor(int $actorId, array $filmData): Movie
    {
        return Movie::updateOrCreate(
            [
                'actor_id' => $actorId,
                'title'    => $filmData['title'] ?? 'Unknown',
            ],
            [
                'year' => isset($filmData['release_date'])
                    ? \Carbon\Carbon::parse($filmData['release_date'])->format('Y')
                    : null,
            ]
        );
    }

    public function saveFromSwapi(array $filmData): Movie
    {
        return Movie::updateOrCreate(
            ['url' => $filmData['url']],
            [
                'title'         => $filmData['title'] ?? 'Unknown',
                'episode_id'    => $filmData['episode_id'] ?? null,
                'opening_crawl' => $filmData['opening_crawl'] ?? null,
                'director'      => $filmData['director'] ?? null,
                'producer'      => $filmData['producer'] ?? null,
                'release_date'  => $filmData['release_date'] ?? null,
            ]
        );
    }

    public function searchByTitle(string $title)
    {
        return Movie::where('title', 'like', "%{$title}%")->get();
    }
}