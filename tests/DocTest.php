<?php
	namespace Stein197\Doxer;

	class DocTest extends BaseCase {

		/**
		 * @dataProvider data
		 */
		public function testGetDescription(string $docblock, array $data): void {
			$this->assertEquals($data['desc'], (new Doc($docblock))->getDescription());
		}

		/**
		 * @dataProvider data
		 */
		public function testGetTags(string $docblock, array $data): void {
			$tags = (new Doc($docblock))->getTags();
			if ($data['tags'])
				$this->assertIsArray($tags);
			else
				$this->assertNull($tags);
		}

		public function testPropertiesAreNull_whenEmptyConstructor(): void {
			$doc = new Doc(null);
			$this->assertNull($doc->getDescription());
			$this->assertNull($doc->getTags());
		}

		public function testGetTags_withFilter(): void {
			$text = <<<DOC
			/**
			 * Desc
			 * @a A1
			 * @a A2
			 * @b B1
			 * @a A3
			 */
			DOC;
			$doc = new Doc($text);
			$this->assertEquals(4, sizeof($doc->getTags()));
			$this->assertEquals(3, sizeof($doc->getTags('a')));
			$this->assertEquals(1, sizeof($doc->getTags('b')));
			foreach ($doc->getTags('a') as $tag)
				$this->assertEquals('a', $tag->getName());
		}
	}
