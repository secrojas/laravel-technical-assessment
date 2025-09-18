<?php

namespace App\Repositories;

use App\Models\Actor;
use App\Repositories\Contracts\ActorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActorRepository implements ActorRepositoryInterface
{
    public function getAllWithMoviesPaginated(int $perPage = 9, ?string $search = null): LengthAwarePaginator
    {
        return Actor::with('movies')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($perPage);
    }

    public function saveFromSwapi(array $data): Actor
    {
        return Actor::updateOrCreate(
            ['swapi_url' => $data['url']],
            [
                'name'       => $data['name'],
                'birthdate'  => $data['birth_year'] ?? null,
                'gender'     => $data['gender'] ?? null,
                'height'     => $data['height'] ?? null,
                'mass'       => $data['mass'] ?? null,
                'hair_color' => $data['hair_color'] ?? null,
                'skin_color' => $data['skin_color'] ?? null,
                'eye_color'  => $data['eye_color'] ?? null,
            ]
        );
    }
}