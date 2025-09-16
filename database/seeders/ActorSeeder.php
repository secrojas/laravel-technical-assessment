<?php

namespace Database\Seeders;

use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class ActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Actor::factory(5)->create()->each(function ($actor) {
            Movie::factory(3)->create(['actor_id' => $actor->id]);
        });
    }
}
