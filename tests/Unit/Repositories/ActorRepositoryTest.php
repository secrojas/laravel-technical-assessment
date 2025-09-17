<?php

namespace Tests\Unit\Repositories;

use App\Repositories\ActorRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_from_swapi_creates_actor()
    {
        $repo = new ActorRepository();

        $actor = $repo->saveFromSwapi([
            'url' => 'https://swapi.dev/api/people/1/',
            'name' => 'Luke Skywalker',
            'birth_year' => '19 BBY',
        ]);

        $this->assertDatabaseHas('actors', ['swapi_url' => 'https://swapi.dev/api/people/1/']);
        $this->assertEquals('Luke Skywalker', $actor->name);
    }
}