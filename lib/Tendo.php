<?php

namespace Tendo;

use Colors\Color;
use Tendo\Reports\ReportInterface;
use Tendo\Reports\CommandLineReport;

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
        // $this->reports[] = [$results['pass'], $results['messages'], $results['stack']];
        // return $this->report($results['pass'], $results['messages'], $results['stack']);
        return $results;
        // }
    }

    // TODO
    // function todo($title) {
    // }

    public function run(ReportInterface $report = null) {
        if (!$report) {
            $report = new CommandLineReport;
        }

        if (strlen($this->title) > 1) {
            $report->onTitle($this->title);
        }

        foreach ($this->tests as $test) {
            $results = $this->test($test['title'], $test['cb']);
            if ($results['pass']) {
                ++$this->results['pass'];
            } else {
                ++$this->results['fail'];
            }
            $report->onTest($test['title'], (bool) $results['pass'], $results['messages'], $results['stack']);
        }

        $report->onResults($this->results['pass'], $this->results['fail']);
    }
}
