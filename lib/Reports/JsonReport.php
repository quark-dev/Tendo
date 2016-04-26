<?php
namespace Tendo\Reports;

class JsonReport implements ReportInterface {
    private $json = '';
    private $title;

    public function __construct() {
        $this->json = ['title' => '', 'tests' => []];
    }

    public function onTitle($title) {
        $this->json['title'] = $title;
    }

    public function onTest($title, $hasPass, $messages = null, array $stack = null) {
        $this->json['tests'][] = [
            'title' => $title,
            'pass' => $hasPass,
            'messages' => $messages,
            'stack' => $stack
        ];
    }

    public function onResults($pass, $fail) {
        $this->json['results'] = ['pass' => $pass, 'fail' => $fail];

        $arg = getopt('o:');
        if ($arg) {
            file_put_contents($arg['e'], json_encode($this->json));
        } else {
            echo json_encode($this->json, JSON_PRETTY_PRINT);
        }
    }
}
