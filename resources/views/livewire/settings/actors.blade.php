<div>
    {{-- Search --}}
    <input 
        type="text" 
        wire:model.live="search" 
        placeholder="Search actors..." 
        class="w-full mb-4 px-4 py-2 border rounded-lg dark:bg-zinc-800 dark:text-white"
    >

    {{-- Actor cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($actors as $actor)
            <div class="p-4 border rounded-lg shadow-sm bg-white dark:bg-zinc-800">
                <h3 class="text-lg font-semibold mb-1">{{ $actor->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                    Born: {{ $actor->birthdate ?? 'Unknown' }}
                </p>

                @if($actor->movies->count() > 0)
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Films:</h4>
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        @foreach($actor->movies as $movie)
                            <li>
                                <span class="font-semibold">{{ $movie->title }}</span>
                                @if($movie->episode_id)
                                    – Episode {{ $movie->episode_id }}
                                @endif
                                @if($movie->release_date)
                                    ({{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }})
                                @endif
                                <br>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Directed by {{ $movie->director ?? '-' }}, 
                                    Produced by {{ $movie->producer ?? '-' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">No films available.</p>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $actors->links() }}
    </div>
</div>
