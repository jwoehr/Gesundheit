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

#### The `.env` file

See the example `example.env`

If you are connecting to a MongoDB installation with a valid certificate, you do not need the two keys:

```properties
mongodb_cert_path=/some/absolute/path
mongodb_ca_cert_path=/some/absolute/path
```

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

### Creating users

Assuming you have a `.env` file in the Gesundheit directory:

- `cd scripts`
- `usermanage ../.env create` _username_ _password_

`usermanage -h` will give you this approximately this usage:

```text
    Usage: ./usermanage.php envpath op [ name ] [ password ]
        where op is one of:
            * list
            * delete name
            * create name password
            * change name password
        Returns:
            0   success
            101 'create' requires name and password
            102 Error creating new user
            103 'delete' requires name
            104 Error deleting user
            105 'password' requires name and password
            106 Error changing password for user
            107 Unknown op
```

## Testing Gesundheit

- Unit Testing
  - `./scripts/runtests.sh gesundheit .env_test` from the root of the repo should do it, assuming you have a `.env_test` file created in the root of the repo.
    - See the `example.env` file in the root of the repo.
    - Create a duplicate test database and point to that in your .`env_test` file.
      - There is a `mongodump`  in the directory `test/dump` of the test database corresponding to the data expected by the tests.

## Document the code

The code is set up for [phpDocumentor](https://phpdoc.org/) ... just install that tool and run the phpDocumentor command and it will generate API documentation in `.doc/`
