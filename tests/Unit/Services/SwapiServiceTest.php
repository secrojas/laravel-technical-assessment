<?php

namespace Tests\Unit\Services;

use App\Repositories\ActorRepository;
use App\Repositories\MovieRepository;
use App\Services\SwapiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SwapiServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_people_returns_empty_when_no_query()
    {
        $service = new SwapiService(new ActorRepository(), new MovieRepository());

        $this->assertSame([], $service->searchPeople(''));
    }

    public function test_search_people_fetches_and_stores_data()
    {
        $baseUrl = config('swapi.base_url');

        Http::fake([
            "{$baseUrl}/people*" => Http::response([
                'results' => [
                    [
                        'name'       => 'Luke Skywalker',
                        'birth_year' => '19 BBY',
                        'gender'     => 'Male',
                        'height'     => '172',
                        'mass'       => '77',
                        'films'      => ["{$baseUrl}/films/1/"],
                        'url'        => "{$baseUrl}/people/1/",
                    ],
                ],
            ], 200),

            "{$baseUrl}/films/1/" => Http::response([
                'title'         => 'A New Hope',
                'episode_id'    => 4,
                'opening_crawl' => 'It is a period of civil war...',
                'director'      => 'George Lucas',
                'producer'      => 'Gary Kurtz, Rick McCallum',
                'release_date'  => '1977-05-25',
                'url'           => "{$baseUrl}/films/1/",
            ], 200),
        ]);

        Cache::flush();

        $service = new SwapiService(new ActorRepository(), new MovieRepository());
        $results = $service->searchPeople('Luke');

        $this->assertNotEmpty($results);
        $this->assertEquals('Luke Skywalker', $results[0]['name']);
        $this->assertEquals('A New Hope', $results[0]['films_details'][0]['title']);
        $this->assertEquals(4, $results[0]['films_details'][0]['episode_id']);
        $this->assertEquals('George Lucas', $results[0]['films_details'][0]['director']);

        $this->assertDatabaseHas('actors', [
            'name' => 'Luke Skywalker',
            'birthdate' => '19 BBY',
        ]);

        $this->assertDatabaseHas('movies', [
            'title'       => 'A New Hope',
            'episode_id'  => 4,
            'director'    => 'George Lucas',
            'producer'    => 'Gary Kurtz, Rick McCallum',
            'release_date'=> '1977-05-25',
            'url'         => "{$baseUrl}/films/1/",
        ]);

        $actorId = \App\Models\Actor::where('name', 'Luke Skywalker')->first()->id;
        $movieId = \App\Models\Movie::where('title', 'A New Hope')->first()->id;

        $this->assertDatabaseHas('actor_movie', [
            'actor_id' => $actorId,
            'movie_id' => $movieId,
        ]);
    }

    public function test_returns_person_without_films_when_no_films_in_response()
    {
        $baseUrl = config('swapi.base_url');

        Http::fake([
            "{$baseUrl}/people*" => Http::response([
                'results' => [
                    [
                        'name'       => 'Han Solo',
                        'birth_year' => '29 BBY',
                        'gender'     => 'Male',
                        'height'     => '180',
                        'mass'       => '80',
                        'films'      => [], // sin films
                        'url'        => "{$baseUrl}/people/14/",
                    ],
                ],
            ], 200),
        ]);

        Cache::flush();

        $service = new SwapiService(new ActorRepository(), new MovieRepository());
        $results = $service->searchPeople('Han');

        $this->assertNotEmpty($results);
        $this->assertEquals('Han Solo', $results[0]['name']);
        $this->assertArrayNotHasKey('films_details', $results[0]);

        $this->assertDatabaseHas('actors', ['name' => 'Han Solo']);
        $this->assertDatabaseCount('movies', 0);
    }

    public function test_uses_cache_for_repeated_queries()
    {
        $baseUrl = config('swapi.base_url');

        Http::fake([
            "{$baseUrl}/people*" => Http::sequence()
                ->push([
                    'results' => [
                        [
                            'name' => 'Leia Organa',
                            'birth_year' => '19 BBY',
                            'gender' => 'Female',
                            'height' => '150',
                            'mass' => '49',
                            'films' => [],
                            'url' => "{$baseUrl}/people/5/",
                        ],
                    ],
                ], 200)
                ->push([], 500),
        ]);

        Cache::flush();

        $service = new SwapiService(new ActorRepository(), new MovieRepository());

        $resultsFirst = $service->searchPeople('Leia');
        $this->assertNotEmpty($resultsFirst);
        $this->assertEquals('Leia Organa', $resultsFirst[0]['name']);
        
        $resultsSecond = $service->searchPeople('Leia');
        $this->assertNotEmpty($resultsSecond);
        $this->assertEquals('Leia Organa', $resultsSecond[0]['name']);
    }
}
