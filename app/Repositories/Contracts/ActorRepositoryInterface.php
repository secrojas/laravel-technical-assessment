<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ActorRepositoryInterface
{    
    public function getAllWithMoviesPaginated(int $perPage = 9, ?string $search = null): LengthAwarePaginator;
    public function saveFromSwapi(array $data);
}
