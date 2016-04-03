<?php

namespace Tendo;

use Colors\Color;

/**
 * Tendo
 */
class Tendo
{
    private $title;

    private $titleTest;

    private $results = [
        'pass' => 0,
        'fail' => 0
    ];

    private $tests;

    // private $assert;

    private $beforeEachFn = null;

    private $afterEachFn = null;

    function __construct($title = '') {
        $this->title = $title;
        $this->tests = new \SplDoublyLinkedList;
        // $this->assert = new Assert;
    }

    function title($title = '') {
        $this->title = $title;
    }

    function beforeEach(callable $cb) {
        $this->beforeEachFn = $cb;
    }

    function afterEach(callable $cb) {
        $this->afterEachFn = $cb;
    }

    function __invoke($title, callable $cb) {
        $this->tests->push([
            'title' => $title,
            'cb' => $cb
        ]);
        // return $this->test($title, $cb);
    }

    function todo($title) {
        $this->tests->push([
            'title' => $title,
            'cb' => null
        ]);
    }

    function test($title, callable $cb = null) {
        $this->titleTest = $title;

        $assert = new Assert;

            if ($this->beforeEachFn) {
                $fn = $this->beforeEachFn;
                $fn($assert);
            }

            if ($cb !== null) {
            $cb($assert);
            }
            $results = $assert->results();

            if ($this->afterEachFn) {
                $fn = $this->afterEachFn;
                $fn($assert);
            }

        // if ($results) {
        return $this->report($results['pass'], $results['messages'], $results['stack']);
        // }
    }

    // TODO
    // function todo($title) {
    // }

    /**
     * Report
     */
    private function report($pass, $messages = null, array $stack = null) {
        $c = new Color();

        if ($pass) {
            echo $c('✓ ')->green() . $this->titleTest;
            foreach ($messages as $msg) {
                if ($msg) {
                    echo PHP_EOL . '  - ' . $msg;
                }
            }
            ++$this->results['pass'];
        } else {
            echo $c('✗ ')->red() . $this->titleTest;
            ++$this->results['fail'];

            if ($stack) {
                echo PHP_EOL . $stack['source'] . PHP_EOL;
                echo $c("({$stack['file']}:{$stack['line']})")->red();
            }
        }

        echo PHP_EOL;
    }

    // at the end
    public function results() {
        $c = new Color();

        echo PHP_EOL;
        if ($this->results['fail'] === 0) {
            echo $c($this->results['pass'] . ' tests passed 👍')->yellow();
        } else {
            echo $c($this->results['pass'] . ' tests passed, ' . $this->results['fail'] . ' tests failed')->yellow();
        }
        echo PHP_EOL;
    }

    public function run() {
        $c = new Color();

        if (strlen($this->title) > 1) {
            echo '# ' . $c($this->title)->bold() . ' #' . PHP_EOL . PHP_EOL;
        }

        foreach ($this->tests as $test) {
            $this->test($test['title'], $test['cb']);
        }

        $this->results();
    }
}
