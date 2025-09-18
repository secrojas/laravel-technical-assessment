<div>
    <div class="flex space-x-2">
        <input 
            type="text" 
            wire:model.defer="query"
            wire:keydown.enter="search"
            placeholder="Search Star Wars character..."
            class="border p-2 w-full"
        >
        <button 
            wire:click="search" 
            class="bg-blue-500 text-white px-4 py-2 rounded">
            Search
        </button>        
    </div>

    {{-- Loading Spinner --}}
    <div wire:loading wire:target="search" 
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="flex flex-col items-center space-y-3">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <span class="text-blue-500 text-lg font-semibold">Loading for results...</span>
        </div>
    </div>

    {{-- Results --}}
    <div class="mt-4 space-y-3">
        @if(!empty($results))
            <p class="text-sm text-gray-600">
                {{ count($results) }} result{{ count($results) > 1 ? 's' : '' }} found
            </p>
        @endif

        @forelse($results as $person)
            <div class="p-4 border rounded shadow bg-white dark:bg-zinc-800 space-y-2">
                <h2 class="font-bold text-lg">{{ $person['name'] }}</h2>
                <p><strong>Birth Year:</strong> {{ $person['birth_year'] ?? 'Unknown' }}</p>
                <p><strong>Gender:</strong> {{ $person['gender'] ?? 'Unknown' }}</p>
                <p><strong>Height:</strong> {{ $person['height'] ?? 'Unknown' }} cm</p>
                <p><strong>Mass:</strong> {{ $person['mass'] ?? 'Unknown' }} kg</p>

                @if(!empty($person['films_details']))
                    <p><strong>Films:</strong></p>
                    <ul class="list-disc ml-5 space-y-1">
                        @foreach($person['films_details'] as $film)
                            <li>
                                <span class="font-semibold">{{ $film['title'] }}</span>
                                @if(!empty($film['episode_id']))
                                    – Episode {{ $film['episode_id'] }}
                                @endif
                                @if(!empty($film['release_date']))
                                    ({{ \Carbon\Carbon::parse($film['release_date'])->format('Y') }})
                                @endif
                                <br>
                                <span class="text-sm text-gray-600">
                                    Directed by {{ $film['director'] ?? '-' }},
                                    Produced by {{ $film['producer'] ?? '-' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @empty
            @if($query)
                <p class="text-red-500">No results found.</p>
            @endif
        @endforelse
    </div>
</div>