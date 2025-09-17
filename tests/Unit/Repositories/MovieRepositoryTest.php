<?php

namespace Tests\Unit\Repositories;

use App\Models\Actor;
use App\Repositories\MovieRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_for_actor_creates_movie()
    {
        $actor = Actor::factory()->create();
        $repo = new MovieRepository();

        $repo->saveForActor($actor->id, [
            'title' => 'A New Hope',
            'release_date' => '1977-05-25',
        ]);

        $this->assertDatabaseHas('movies', [
            'actor_id' => $actor->id,
            'title'    => 'A New Hope',
        ]);
    }
}