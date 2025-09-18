<?php

namespace Tests\Unit\Livewire;

use App\Livewire\SwapiSearch;
use App\Services\SwapiService;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class SwapiSearchComponentUnitTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calls_service_and_displays_results(): void
    {
        $mockService = Mockery::mock(SwapiService::class);
        $this->app->instance(SwapiService::class, $mockService);

        $mockService->shouldReceive('searchPeople')
            ->with('Luke')
            ->andReturn([
                [
                    'name' => 'Luke Skywalker',
                    'birth_year' => '19 BBY',
                    'films_details' => [
                        ['title' => 'A New Hope', 'release_date' => '1977-05-25'],
                    ],
                ],
            ]);

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Luke')
            ->call('search')
            ->assertSee('Luke Skywalker')
            ->assertSee('19 BBY')
            ->assertSee('A New Hope');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_empty_results(): void
    {
        $mockService = Mockery::mock(SwapiService::class);
        $this->app->instance(SwapiService::class, $mockService);

        $mockService->shouldReceive('searchPeople')
            ->with('Unknown')
            ->andReturn([]);

        Livewire::test(SwapiSearch::class)
            ->set('query', 'Unknown')
            ->call('search')
            ->assertSee('No results found');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_empty_results_when_query_is_empty(): void
    {
        $mockService = \Mockery::mock(\App\Services\SwapiService::class);
        $this->app->instance(\App\Services\SwapiService::class, $mockService);

        $mockService->shouldReceive('searchPeople')
            ->once()
            ->with('')
            ->andReturn([]);

        Livewire::test(\App\Livewire\SwapiSearch::class)
            ->set('query', '')
            ->call('search')
            ->assertDontSee('No results found.')
            ->assertStatus(200);
    }
}
