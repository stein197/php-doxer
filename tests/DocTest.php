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
	}
