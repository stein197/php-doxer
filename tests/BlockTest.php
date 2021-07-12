<?php
	namespace Stein197\Doxer;

	class BlockTest extends BaseCase {

		/**
		 * @dataProvider data
		 */
		public function testGetDescription(string $docblock, array $data): void {
			$this->assertEquals($data['desc'], (new Block($docblock))->getDescription());
		}

		/**
		 * @dataProvider data
		 */
		public function testGetTags(string $docblock, array $data): void {
			if ($data['tags'])
				$this->assertIsArray((new Block($docblock))->getTags());
			else
				$this->assertNull((new Block($docblock))->getTags());
		}
	}
