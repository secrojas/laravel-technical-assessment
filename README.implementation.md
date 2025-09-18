# Implementation Details

## 🎬 Overview

This project demonstrates how to manage a catalog of actors and movies combined with data fetched from the Star Wars API (SWAPI).

* At startup, the database is seeded with random actors and films, ensuring there is content to browse and interact with.
* In the Actors & Movies section, users can browse all stored actors, filter them by name, and view the films each actor is associated with.
* In the Star Wars Search section, users can search for any character from the SWAPI API. The app will display the character details (birth year, gender, height, etc.), together with their films.
* Every search result from SWAPI is persisted into the local database so that searched characters and their related movies can later be visualized in the Actors & Movies section.

## 🛠️ Technical Design

* Laravel + Livewire
    * We use Livewire components (Actors, SwapiSearch) to provide a reactive UI without leaving Blade templates. This allows fast filtering, searching, and paginating.

* Service & Repository Pattern
    * ActorService and SwapiService encapsulate business logic.
    * ActorRepository and MovieRepository handle direct database queries.
    * This separation improves testability and makes the codebase easier to maintain.

* Database
    * actors, movies, and the pivot table actor_movie manage the relationships.
    * Seeding provides an initial dataset of random actors and movies.

* Caching
    * When fetching from SWAPI, results are cached to reduce redundant API calls and improve performance.
    * Repeated queries first hit the cache before reaching the API.

* Persistence of External Data
    * Characters retrieved from SWAPI are stored locally with their related films.
    * This ensures that once fetched, data is available for browsing in the main Actors & Movies section.

* Testing
    * Unit tests cover services (ActorService, SwapiService) to ensure database and API logic behaves correctly.
    * Feature and Livewire tests validate end-to-end behavior, including UI rendering, filtering, pagination, and integration with SWAPI.

## 🚀 Usage Flow

1-Browse existing Actors & Movies (seeded data).
2-Use the search box to filter actors by name.
3-Go to Star Wars Search, type a character’s name, and view results directly from SWAPI.
4-The character and their films are stored in the DB and will now appear in the Actors & Movies list.


## 🔑 Environment Variables
In addition to the default `.env` configuration, the following variable is required:

```env
SWAPI_BASE_URL=https://swapi.dev/api
```

### Run tests with:

```
./vendor/bin/sail artisan test
``` 
