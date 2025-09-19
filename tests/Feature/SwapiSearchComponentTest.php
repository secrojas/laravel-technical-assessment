<?php

namespace Tests\Feature;

use App\Livewire\SwapiSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use Illuminate\Support\Facades\Http;

class SwapiSearchComponentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_displays_results_from_swapi()
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

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Luke')
            ->call('search')
            ->assertSee('Luke Skywalker')
            ->assertSee('A New Hope')
            ->assertSee('Ep 4')
            ->assertSee('George Lucas')
            ->assertSee('Gary Kurtz, Rick McCallum')
            ->assertSee('1977');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_shows_no_results_when_swapi_returns_empty()
    {
        $baseUrl = config('swapi.base_url');

        Http::fake([
            "{$baseUrl}/people*" => Http::response(['results' => []], 200),
        ]);

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Unknown Character')
            ->call('search')
            ->assertSee('No results found.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_api_failure_gracefully()
    {
        $baseUrl = config('swapi.base_url');

        Http::fake([
            "{$baseUrl}/people*" => Http::response([], 500),
        ]);

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Luke')
            ->call('search')
            ->assertSee('No results found.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_character_without_films()
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
                        'films'      => [],
                        'url'        => "{$baseUrl}/people/14/",
                    ],
                ],
            ], 200),
        ]);

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Han')
            ->call('search')
            ->assertSee('Han Solo')
            ->assertDontSee('Films:');
    }
}
