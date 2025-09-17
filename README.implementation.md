# Implementation Notes

This document describes the main improvements and architectural decisions applied to the Laravel Technical Assessment.

---

## 🔑 Environment Variables
In addition to the default `.env` configuration, the following variable is required:

```env
SWAPI_BASE_URL=https://swapi.dev/api
```

### 🏗️ Architecture Applied

* Service Layer
    * SwapiService handles communication with SWAPI, caching, and orchestration of repositories.
* Repository Layer
    * ActorRepository and MovieRepository encapsulate database operations, following the Repository pattern.
* Interface Binding
    * SwapiServiceInterface ensures that the Livewire component depends on an abstraction.
    * Registered in the container via AppServiceProvider.
* Livewire Component
    * SwapiSearch handles the UI logic only and calls the SwapiService.

### 🧪 Testing
* Unit Tests:
    * Cover SwapiService, ActorRepository, and MovieRepository.
* Feature Tests:
    * Cover Livewire component with mocked service.
* Integration Tests:
    * Validate persistence of actors and movies when fetching from SWAPI. 


### Run tests with:

```
./vendor/bin/sail artisan test
``` 
