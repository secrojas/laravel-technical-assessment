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
}