<?php
use Tendo\Tendo;

require 'vendor/autoload.php';

$test = new Tendo();

$test('title', function($t) {
    $t->pass();
});

$test('title 2', function($t) {
    $t->is(123, '123');
});

$test('Plan', function($t) {
    $t->plan(2);

    for ($i = 0; $i < 3; $i++) {
        $t->true($i < 3);
    }
}); // fails, 3 assertions are executed which is too many

$test->results();
