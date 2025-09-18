<?php

namespace Tests\Unit\Repositories;

use App\Models\Actor;
use App\Repositories\MovieRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_from_swapi_creates_movie_and_links_actor()
    {
        $actor = Actor::factory()->create();
        $repo = new MovieRepository();

        $filmData = [
            'title'         => 'A New Hope',
            'episode_id'    => 4,
            'opening_crawl' => 'It is a period of civil war...',
            'director'      => 'George Lucas',
            'producer'      => 'Gary Kurtz, Rick McCallum',
            'release_date'  => '1977-05-25',
            'url'           => 'https://swapi.dev/api/films/1/',
        ];

        $movie = $repo->saveFromSwapi($filmData);

        $movie->actors()->attach($actor->id);

        $this->assertDatabaseHas('movies', [
            'title'        => 'A New Hope',
            'episode_id'   => 4,
            'director'     => 'George Lucas',
            'producer'     => 'Gary Kurtz, Rick McCallum',
            'release_date' => '1977-05-25',
            'url'          => 'https://swapi.dev/api/films/1/',
        ]);

        $this->assertDatabaseHas('actor_movie', [
            'actor_id' => $actor->id,
            'movie_id' => $movie->id,
        ]);
    }
}
