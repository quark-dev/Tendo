# Tendo

Tendo is a really simple testing framework inspired by [Ava](https://github.com/sindresorhus/ava).

# How to run

```
composer require 'quarkdev/tendo'
```

## Example

```php
<?php
use Tendo\Tendo;

require 'vendor/autoload.php';

$test = new Tendo();

$test('title', function($t) {
    $t->pass(); // yes
});

$test('title 2', function($t) {
    $t->is(123, '123'); // nop
});

$test('Plan', function($t) {
    $t->plan(2);

    for ($i = 0; $i < 3; $i++) {
        $t->true($i < 3);
    }
}); // fails, 3 assertions are executed which is too many

$test->results();
```

## Command line example

The `$test` variable is auto injected, you dont have to instantiate Tendo.

Example with `myTest.php`:

```php
<?php
$test('title', function($t) {
    $t->pass(); // yes
});

$test('title 2', function($t) {
    $t->is(123, '123'); // nop
});
```

You can now run the test via the command line :

```bash
./vendor/bin/tendo myTest.php
```

*Note*: You can run multiple tests via the commande line `./vendor/bin/tendo test1.php test2.php ...`.
