<?php

namespace App\Console\Commands;

use App\Models\Actor;
use Illuminate\Console\Command;

class ListActorsWithMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actors:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all actors with their movies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actors = Actor::with('movies')->get();

        foreach ($actors as $actor) {
            $this->info("🎬 Actor: {$actor->name} ({$actor->birthdate})");

            foreach ($actor->movies as $movie) {
                $this->line("   - {$movie->title} ({$movie->release_date})");
            }

            $this->newLine();
        }

        return Command::SUCCESS;
    }
}
