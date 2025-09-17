<?php

namespace App\Repositories;

use App\Models\Actor;

class ActorRepository
{
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