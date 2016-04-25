<?php
namespace Tendo\Reports;

interface ReportInterface {
    public function onTitle($title);

    public function onTest($title, $hasPass, $messages = null, array $stack = null);

    public function onResults($pass, $fail);
}
