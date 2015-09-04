BDSM Does Simple Migrations
=============================

[![Travis CI](https://api.travis-ci.org/turanct/bdsm.svg?branch=master)](https://travis-ci.org/turanct/bdsm)

A really simple migrations library. It is currently *not meant to be used in production* but nobody's going to stop you if you really want to. Use at your own risk.


Usage
-----------------------------

### Installing

Installing it is easy, just require `turanct/bdsm` as a development dependency in your `composer.json` file, and configure a `bin-dir`. The bdsm executable will be available in your bin directory when you've run `composer install`.

```json
{
    "require-dev": {
        "turanct/bdsm": "dev-master"
    },
    "config": {
        "bin-dir": "bin"
    }
}
```

### Bootstrapping

The `bdsm` executable expects a `.bdsm.php` file in the working directory of your project. In this file you can do all necessary bootstrapping. It just needs to return an array with these elements:

1. The directory where we can find migrations
2. The file in which we can log which migrations we ran
3. A database adapter, implementing the really simple `Bdsm\Database` interface

```php
<?php

use Bdsm\Database;

final class MyDatabaseAdapter implements Database
{
    public function query($query)
    {
        var_dump($query);
    }
}

return array(
    __DIR__ . '/migrations',
    __DIR__ . '/.bdsm.log.json',
    new MyDatabaseAdapter(),
);

```

In this case, we provided a directory `migrations` in which the migrations will live, and a log file `.bdsm.log.json` to which the system can log. The database adapter in this example will not actually connect to a database, but it will print out every query issued.

### First migration

We're ready to create our first migration right now! Run the `bdsm` command with the `create` parameter.

```sh
$ bin/bdsm create
Migration "Migration1441375998" created...
```

You can also pass a custom migration name as a parameter. A new migration will be created in our migrations directory with these contents:

```php
<?php

use Bdsm\Migration;
use Bdsm\Database;

final class Migration1441375998 implements Migration
{
    public function up(Database $database)
    {
    }

    public function down(Database $database)
    {
    }
}
```

We can now simply script the up and down actions for our migration, and then check it's status. It should be red.

```sh
$ bin/bdsm status
✘ Migration1441375998
```

Running it is as simple as `bdsm up Migration1441375998`. Note that you can run all migrations at once using `bdsm up` without arguments.

```sh
$ bin/bdsm up Migration1441375998
Do you want to migrate UP migration Migration1441375998? (Y/n)

Migrated...
```

Another look at the migration status shows us that we succeeded!

```sh
$ bin/bdsm status
✔︎ Migration1441375998
```

Migrating down is exactly the same procedure, but with `down` instead of `up` commands.


Tests
-----------------------------

BDSM has unit tests, located in the `tests` directory. These tests are written using phpunit. To run them, make sure you did a `composer install` and run phpunit.

```sh
$ bin/phpunit
```


Contributing
-----------------------------

Feel free to fork and send pull requests!


License
-----------------------------

This library is MIT licensed. See also LICENSE.md
