<?php
namespace Tendo\Reports;

use Colors\Color;

class CommandLineReport implements ReportInterface {

    public function onTitle($title) {
        $c = new Color();

        if (strlen($title) > 1) {
            echo '# ' . $c($title)->bold() . ' #' . PHP_EOL . PHP_EOL;
        }
    }

    /**
     * Report
     */
    public function onTest($title, $hasPass, $messages = null, array $stack = null) {
        $c = new Color();

        if ($hasPass) {
            echo $c('✓ ')->green() . $title;
            foreach ($messages as $msg) {
                if ($msg) {
                    echo PHP_EOL . '  - ' . $msg;
                }
            }
        } else {
            echo $c('✗ ')->red() . $title;

            if ($stack) {
                echo PHP_EOL . $stack['source'] . PHP_EOL;
                echo $c("({$stack['file']}:{$stack['line']})")->red();
            }
        }

        echo PHP_EOL;
    }

    public function onResults($pass, $fail) {
        $c = new Color();

        echo PHP_EOL;
        if ($fail === 0) {
            echo $c($pass . ' tests passed 👍')->yellow();
        } else {
            echo $c($pass . ' tests passed, ' . $fail . ' tests failed')->yellow();
        }
        echo PHP_EOL;
    }
}
