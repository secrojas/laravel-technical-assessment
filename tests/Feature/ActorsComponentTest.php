<?php

namespace Tests\Feature;

use App\Livewire\Actors;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Actor;
use App\Models\Movie;

class ActorsComponentTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_displays_all_actors_with_movies()
    {
        $actor = Actor::factory()->create(['name' => 'Test Actor']);
        Movie::factory()->create(['title' => 'Test Movie', 'actor_id' => $actor->id]);

        Livewire::test(Actors::class)
            ->assertSee('Test Actor')
            ->assertSee('Test Movie');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_filters_actors_by_name()
    {
        $actor1 = Actor::factory()->create(['name' => 'Luke Skywalker']);
        $actor2 = Actor::factory()->create(['name' => 'Darth Vader']);

        Livewire::test(Actors::class)
            ->set('search', 'Luke')
            ->assertSee('Luke Skywalker')
            ->assertDontSee('Darth Vader');
    }
}