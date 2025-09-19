<?php

namespace Tests\Unit\Livewire;

use App\Livewire\Actors;
use App\Models\Actor;
use App\Services\ActorService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class ActorsComponentUnitTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_calls_service_with_search_and_pagination(): void
    {
        $collection = new Collection([
            new Actor(['name' => 'Mocked Actor'])
        ]);

        $fakePaginator = new LengthAwarePaginator(
            $collection,
            $collection->count(), // total
            9,                    // perPage
            1                     // currentPage
        );


        $mockService = Mockery::mock(ActorService::class);
        $this->app->instance(ActorService::class, $mockService);

        $mockService->shouldReceive('listActorsWithMovies')
            ->with(9, '')
            ->andReturn($fakePaginator);

        $mockService->shouldReceive('listActorsWithMovies')
            ->with(9, 'Leo')
            ->andReturn($fakePaginator);

        Livewire::test(Actors::class)
            ->assertStatus(200)
            ->set('search', 'Leo')
            ->assertStatus(200);
    }
}
