<div>
    <input 
        type="text" 
        wire:model.live="search" 
        placeholder="Search actors..." 
        class="border p-2 mb-4 w-full"
    />

    <div class="space-y-4">
        @forelse($actors as $actor)
            <div class="p-4 border rounded">
                <h2 class="font-bold">{{ $actor->name }}</h2>
                <p class="text-sm text-gray-500">Born: {{ $actor->birthdate }}</p>
                <ul class="ml-4 list-disc">
                    @foreach($actor->movies as $movie)
                        <li>{{ $movie->title }} ({{ $movie->year }})</li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p>No actors found.</p>
        @endforelse
    </div>
</div>
