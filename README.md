# Gesundheit

PHP + MongoDB "create your own framework" demo.  
Copyright &copy; 2025 Jack Woehr [jwoehr@softwoehr.com](mailto:jwoehr@softwoehr.com)

- **Not production ready**
- **Not secure**
- **See `LICENSE`** &mdash; no guarantees, no warranties, etc.

## Installation

### Where it resides

This demo was coded for the default Apache server under Ubuntu but should run on any Apache server as typically installed in a Linux distro.
On my system it starts in `/var/www/html/Gesundheit`

- Assumes the `.env` file resides one directory level above the docroot
  - E.g., if running in the default Apache instance in `/var/www/html/Gesundheit` the `.env` should be in `/var/www/`

### Clone and install

As noted, we'll assume you're installing into the default Apache instance which resides in `/var/www/` and whose docroot is `/var/www/html/`

1. Clone the repo
    - We'll refer to the top-level directory of the repo as `Gesundheit`
1. Create the collections
    - Use Compass to create the database
    - Use `scripts/create_collections.js` to create the collections with validation.
        - You'll need to run `npm install` in the `scripts` directory.
1. Copy the `Gesundheit/Gesundheit` directory to `/var/www/html/Gesundheit`
1. Copy `Gesundheit/composer.json` to `/var/www`
1. Run `composer install`
    - You _do_ have [PHP Composer](https://getcomposer.org/), right?!

## Running Gesundheit

[http://localhost/Gesundheit](http://localhost/Gesundheit) should do it.

## Testing Gesundheit

- Unit Testing
  - `./scripts/runtests.sh gesundheit .env_test` from the root of the repo should do it, assuming you have a `.env_test` file created in the root of the repo.
    - See the `example.env` file in the root of the repo.
    - Create a duplicate test database and point to that in your .`env_test` file.
      - There is a `mongodump`  in the directory `test/dump` of the test database corresponding to the data expected by the tests.
