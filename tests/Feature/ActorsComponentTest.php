<?php

namespace Tests\Feature;

use App\Livewire\Actors;
use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ActorsComponentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_displays_all_actors_with_movies(): void
    {
        $actor = Actor::factory()->create(['name' => 'Test Actor']);
        $movie = Movie::factory()->create(['title' => 'Test Movie']);
        $actor->movies()->attach($movie->id);

        Livewire::test(Actors::class)
            ->assertSee('Test Actor')
            ->assertSee('Test Movie');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_filters_actors_by_name(): void
    {
        Actor::factory()->create(['name' => 'Luke Skywalker']);
        Actor::factory()->create(['name' => 'Darth Vader']);

        Livewire::test(Actors::class)
            ->set('search', 'Luke')
            ->assertSee('Luke Skywalker')
            ->assertDontSee('Darth Vader');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_actor_without_movies(): void
    {
        Actor::factory()->create(['name' => 'No Films Actor']);

        Livewire::test(Actors::class)
            ->assertSee('No Films Actor')
            ->assertSee('No films available');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_paginates_actors_list(): void
    {
        $actors = Actor::factory(15)->create();

        Livewire::test(Actors::class)
            ->assertSee($actors[0]->name)
            ->assertSee($actors[8]->name)
            ->assertDontSee($actors[9]->name);

        Livewire::test(Actors::class)
            ->call('gotoPage', 2)
            ->assertSee($actors[9]->name)
            ->assertSee($actors[14]->name)
            ->assertDontSee($actors[0]->name);
    }
}
