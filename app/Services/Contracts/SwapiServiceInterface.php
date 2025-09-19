<?php

namespace App\Services\Contracts;

interface SwapiServiceInterface
{
    public function searchPeople(string $query): array;
}