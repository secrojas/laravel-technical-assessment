# Laravel Technical Assessment

## Instructions
1. Create a new repository in your Github account by cloning this repo
2. Establish an initial commit with the provided code.
3. Complete the functional requirements below & commit these changes to your repository. Open a PR with your changes so that they can be reviewed.
4. Share your repository with us.

## Setup

### System Requirements
-   Docker Desktop
-   WSL2 (Windows only)

Start by forking the repository to your own GitHub account. Then clone the forked repository to your local machine.

```bash
git clone https://github.com/your-username/laravel-assessment-template
cd laravel-technical-assessment
```

We use [Laravel Sail](https://laravel.com/docs/12.x/sail) so you should have good working knowledge of Docker. Start by copying over your env example file.

```bash
cp .env.example .env
```

And then let's install sail.

### Running the application

Start the application using sail.

You should now be able to access the application at `http://localhost`.

You can stop the application with `Ctrl+C` and then run `docker compose down` to remove the containers.

## Requirements

This assessment is to see how your write clean, readable code, and how you structure your application. We're not awarding points on frontend styling but you can use whatever framework if you like.

The application should:

-   Seed a database with at least 5 actors and at least 3 movies per actor
-   Use Eloquent to define the relationship between actors and movies
-   Have a view that displays the list of actors and their associated movies
-   Also include one input that allows the list of actors to be filtered. This can either be done with just PHP or with JavaScript, whichever you prefer. We're just looking for the end result.
-   Have a view with one input that allows the user to search people via the Star Wars API (SW-API: https://swapi.dev/documentation#people) and then displays the data.
    -   The API call should be done on the back end.
    -   The SW-API call is a performance bottleneck, please implement some enhancements that will improve response times within our application
-   Build some test cases which cover the actor/movie filtering behaviour using `Livewire::test()`
-   Build some test cases which mock the SW-API and confirm the search and display works there as intended

You should use [Laravel Livewire](https://livewire.laravel.com/) for your views and actions.

## Implementation Notes

For details about what was applied and implemented in this solution (architecture decisions, environment variables, testing notes, etc.), see the [Implementation README](README.implementation.md).
