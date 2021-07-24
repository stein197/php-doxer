<?php
	namespace Stein197\Doxer;

	/**
	 * The main class that whould be used to parse php docblocks. To parse any doc comment, just instantiate the class
	 * and pass the comment as the only argument and use it:
	 * ```php
	 * (new Block('/** Some text * /'))->getDescription(); // 'Some text'
	 * ```
	 * @package Stein197\Doxer
	 */
	class Doc extends Parser {

		use Descriptable;

		/** @var Tag[] $tags Docblock tags */
		private ?array $tags = [];

		/**
		 * Creates parsed docblock info from docblock comment
		 * @param string $doc Docblock comment
		 */
		public function __construct(string $doc) {
			if ($doc)
				$this->parse($doc);
			else
				$this->description = $this->tags = null;
		}

		/**
		 * Returns docblock tags in order they declared.
		 * @param null|string $name Returns tags with specified name.
		 * @return null|Tag[] Array of tags or null if not found.
		 */
		public function getTags(?string $name = null): ?array {
			return $name ? (array_filter($this->tags, fn (Tag $tag): bool => $tag->getName() === $name) ?: null) : $this->tags;
		}

		protected function parse(string $doc): void {
			$lines = self::lines($doc);
			$isTagArea = false;
			$curTagString = '';
			foreach ($lines as $ln) {
				$isTagLine = str_starts_with($ln, '@');
				if ($isTagLine) {
					$isTagArea = true;
					if ($curTagString)
						$this->tags[] = new Tag($curTagString);
					$curTagString = $ln;
				} else {
					if ($isTagArea) {
						$curTagString .= " $ln";
					} else {
						$this->description .= " $ln";
					}
				}
			}
			$this->description = str_replace(" \n", "\n", ltrim($this->description)) ?: null;
			$this->tags = $this->tags ?: null;
		}

		/**
		 * 
		 * @param string $doc
		 * @return string[]
		 */
		private static function lines(string $doc): array {
			return array_map(
				fn (string $ln): string => rtrim(
					preg_replace(
						'/^\s*\*+\s*/',
						'',
						$ln
					)
				),
				explode(
					"\n",
					preg_replace(
						'/(?:^\/\*{2}[\s\n]*|[\s\n]*\*\/$)/',
						'',
						$doc
					)
				)
			);
		}
	}
