<?php
	namespace Stein197\Doxer;

	/**
	 * Parser base class.
	 * @package Stein197\Doxer
	 */
	abstract class Parser {

		/**
		 * Main method of the parser.
		 * @param string $doc String to parse.
		 * @return void
		 */
		protected abstract function parse(string $doc): void;
	}
