# Recovery Brands Meeting Manager

Recovery Brands is an application run with Laravel that allows the user to retrieve a sorted list of Alcoholics Anonymous / Narcotics Anonymous meetings, by Location and Day.

It uses Guzzle for a simple API Interface, Google Maps API for retrieving location data, PHPSpec for Unit Testing, GuzzleCaching to cache API requests, and Flysystem for filesystem modification (store caching).

It shows: 
- use of a framework
- use of third party libraries
- design patterns

Items to iterate to (future):

- dependency management
- caching
- unit testing

Just require the dependencies with composer, require the needed namespaces, and run the app!