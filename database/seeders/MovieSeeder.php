<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = Movie::factory()->count(10)->create();

        $actors = Actor::all();

        $movies->each(function (Movie $movie) use ($actors) {
            $movie->actors()->attach(
                $actors->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
