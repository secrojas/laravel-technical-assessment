<?php

namespace App\Services;

use App\Repositories\Contracts\ActorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActorService
{
    protected ActorRepositoryInterface $actorRepo;

    public function __construct(ActorRepositoryInterface $actorRepo)
    {
        $this->actorRepo = $actorRepo;
    }

    /**
     * Get all actors with their movies paginated.
     *
     * @param int $perPage
     * @param string|null $search
     * @return LengthAwarePaginator
     */
    public function listActorsWithMovies(int $perPage = 9, ?string $search = null): LengthAwarePaginator
    {
        return $this->actorRepo->getAllWithMoviesPaginated($perPage, $search);
    }
}
