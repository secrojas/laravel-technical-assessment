<div>

    <div class="relative mb-6">
        <input 
            type="text" 
            wire:model.live="search" 
            placeholder="Search actors..." 
            class="w-full pl-10 pr-4 py-2 border rounded-xl shadow-sm 
                   focus:ring-2 focus:ring-blue-500 focus:outline-none 
                   dark:bg-zinc-800 dark:text-white"
        >
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($actors as $actor)
            <div class="p-6 border rounded-xl shadow-md hover:shadow-lg bg-white dark:bg-zinc-800 transition">

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $actor->name }}
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Born: {{ $actor->birthdate ?? 'Unknown' }}
                </p>

                @if($actor->movies->count() > 0)
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Films</h4>
                    <div class="flex flex-col gap-3">
                        @foreach($actor->movies as $movie)
                            <div class="p-3 bg-gray-100 dark:bg-zinc-700 rounded-lg">
                                <span class="block font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $movie->title }}
                                    @if($movie->episode_id)
                                        – Ep {{ $movie->episode_id }}
                                    @endif
                                    @if($movie->release_date)
                                        ({{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }})
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    🎬 Directed by {{ $movie->director ?? '-' }}<br>
                                    🏭 Produced by {{ $movie->producer ?? '-' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                        No films available.
                    </p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $actors->links() }}
    </div>
</div>
