<div>
    
    <div class="relative mb-6 flex">
        <input 
            type="text" 
            wire:model.defer="query"
            wire:keydown.enter="search"
            placeholder="Search Star Wars character..." 
            class="flex-1 pl-10 pr-4 py-2 border rounded-l-xl shadow-sm 
                   focus:ring-2 focus:ring-blue-500 focus:outline-none 
                   dark:bg-zinc-800 dark:text-white"
        >
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
        </span>
        <button 
            wire:click="search" 
            class="bg-blue-500 text-white px-6 rounded-r-xl hover:bg-blue-600 transition">
            Search
        </button>
    </div>

    <div wire:loading wire:target="search" 
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="flex flex-col items-center space-y-3">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <span class="text-blue-500 text-lg font-semibold">Loading results...</span>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($results as $person)
            <div class="p-6 border rounded-xl shadow-md hover:shadow-lg bg-white dark:bg-zinc-800 transition space-y-2">
                
                <h2 class="font-bold text-xl text-gray-900 dark:text-white">{{ $person['name'] }}</h2>

                
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Birth Year:</strong> {{ $person['birth_year'] ?? 'Unknown' }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Gender:</strong> {{ $person['gender'] ?? 'Unknown' }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Height:</strong> {{ $person['height'] ?? 'Unknown' }} cm
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Mass:</strong> {{ $person['mass'] ?? 'Unknown' }} kg
                </p>
                
                @if(!empty($person['films_details']))
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-3">Films</h4>
                    <div class="flex flex-col gap-2">
                        @foreach($person['films_details'] as $film)
                            <div class="p-3 bg-gray-100 dark:bg-zinc-700 rounded-lg">
                                <span class="block font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $film['title'] }}
                                    @if(!empty($film['episode_id']))
                                        – Ep {{ $film['episode_id'] }}
                                    @endif
                                    @if(!empty($film['release_date']))
                                        ({{ \Carbon\Carbon::parse($film['release_date'])->format('Y') }})
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    🎬 Directed by {{ $film['director'] ?? '-' }},
                                    🏭 Produced by {{ $film['producer'] ?? '-' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            @if($query)
                <p class="text-red-500 text-center col-span-full">No results found.</p>
            @endif
        @endforelse
    </div>
</div>
