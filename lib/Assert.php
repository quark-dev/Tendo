<?php

namespace Tendo;

/**
 * Assert
 * @author Fabien Sa
 */
class Assert {

	private $plan;
	private $tests;
	public $context;

	function __construct() {
		$this->context = new \stdClass;
	}

	/**
	 * Pass
	 * @param  string $msg optional Message
	 */
	public function pass($msg = '') {
		$this->recordtest(true, $msg);
	}

	/**
	 * Fail
	 * @param  string $msg optional Message
	 */
	public function fail($msg = '') {
		$this->recordtest(false, $msg);
	}

	/**
	 * True
	 * @param  mixed $exp Expression to test
	 * @param  string $msg optional Message
	 */
	public function true($exp, $msg = '') {
		$this->recordtest($exp, $msg);
	}

	public function false($exp, $msg = '') {
		$this->recordtest(!$exp, $msg);
	}

	/**
	 * Assert that value is equal to expected.
	 * @param  mixed  $a
	 * @param  mixed  $b
	 * @param  string  $msg [description]
	 */
	public function is($a, $b, $msg = '') {
		$this->recordtest($a === $b, $msg);
	}

	/**
	 * Assert that value is not equal to expected.
	 * @param  mixed $a
	 * @param  mixed $b
	 * @param  string $msg [description]
	 */
	public function not($a, $b, $msg = '') {
		$this->recordtest($a !== $b, $msg);
	}

	/**
	 * Plan
	 * Ensure tests only pass when a specific number of assertions have been executed.
	 *
	 * @param  number $num
	 */
	public function plan($num) {
		$this->plan = $num;
	}

	/**
	 * Assert that contents matches regex pattern
	 * @param  string $content Content
	 * @param  string $pattern Regex pattern
	 * @param  string $msg     optional Message
	 */
	public function regex($content, $pattern, $msg = '') {
		$this->recordtest(preg_match($pattern, $content), $msg);
	}

	private function recordtest($pass, $message = '') {
		// >= -> because it will be added
		if (is_numeric($this->plan) && count($this->tests) >= $this->plan) {
			$pass = false;
		}
		$this->tests[] = [
			'pass' => $pass,
			'message' => $message,
			'stack' => debug_backtrace()[1]
		];
	}

	public function results() {
		if (!$this->tests) {
			return;
		}

		$messages = [];
		// return $this->tests;
		foreach ($this->tests as $key => $test) {
			$messages[] = $test['message'];

			if (!$test['pass']) {

				$stack = $test['stack'];
				$stackOut['file'] = $stack['file'];
				$stackOut['line'] = $stack['line'];
				$stackOut['source'] = $this->getFileLine($stack['file'], $stack['line']);

				return ['pass' => false, 'messages' => $messages, 'stack' => $stackOut];
			}
		}

		return [
			'pass' => true,
			'messages' => $messages,
			'stack' => null
		];
	}

	/**
	 * Get file line
	 * @param  string $file File path
	 * @param  number $line Line number
	 * @return string File line
	 */
	public function getFileLine($file, $line) {
		$file = file($file);
		return trim($file[$line - 1]);
	}
}
