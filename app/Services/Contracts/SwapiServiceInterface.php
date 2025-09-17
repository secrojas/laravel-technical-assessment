<?php

namespace App\Services\Contracts;

interface SwapiServiceInterface
{
    /**
     * Search for people in SWAPI by query string.
     *
     * @param string $query
     * @return array
     */
    public function searchPeople(string $query): array;
}