<?php

namespace Tests\Unit\Services;

use App\Models\Actor;
use App\Models\Movie;
use App\Repositories\ActorRepository;
use App\Services\ActorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_actors_with_movies_returns_all_with_relations()
    {
        $actor = Actor::factory()->create(['name' => 'Leonardo DiCaprio']);
        $movie = Movie::factory()->create(['title' => 'Inception', 'release_date' => 2010]);
        $actor->movies()->attach($movie);

        $service = new ActorService(new ActorRepository());
        
        $results = $service->listActorsWithMovies(); // perPage=9 por defecto

        $this->assertEquals(1, $results->total());
        $this->assertEquals('Leonardo DiCaprio', $results->first()->name);
        $this->assertEquals('Inception', $results->first()->movies->first()->title);
        $this->assertEquals(2010, $results->first()->movies->first()->release_date);
    }

    public function test_list_actors_with_movies_applies_search_filter()
    {
        Actor::factory()->create(['name' => 'Leonardo DiCaprio']);
        Actor::factory()->create(['name' => 'Tom Hanks']);

        $service = new ActorService(new ActorRepository());

        $results = $service->listActorsWithMovies(9, 'Leonardo');

        $this->assertEquals(1, $results->total());
        $this->assertEquals('Leonardo DiCaprio', $results->first()->name);
    }

    public function test_list_actors_with_movies_paginates_results()
    {
        Actor::factory()->count(15)->create();

        $service = new ActorService(new ActorRepository());

        $results = $service->listActorsWithMovies(10);

        $this->assertEquals(10, $results->perPage());
        $this->assertCount(10, $results);
    }
}
