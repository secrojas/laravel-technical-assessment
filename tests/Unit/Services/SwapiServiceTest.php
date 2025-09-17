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
                'title'        => 'A New Hope',
                'release_date' => '1977-05-25',
            ], 200),
        ]);

        Cache::flush();

        $service = new SwapiService(new ActorRepository(), new MovieRepository());
        $results = $service->searchPeople('Luke');

        $this->assertNotEmpty($results);
        $this->assertEquals('Luke Skywalker', $results[0]['name']);
        $this->assertEquals('A New Hope', $results[0]['films_details'][0]['title']);

        $this->assertDatabaseHas('actors', ['name' => 'Luke Skywalker']);
        $this->assertDatabaseHas('movies', ['title' => 'A New Hope']);
    }
}
