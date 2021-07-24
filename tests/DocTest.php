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

		public function testGetTags_withName(): void {
			$doc = <<<DOCBLOCK
			/**
			 * Description
			 * @a Desc a 1
			 * @b Desc b 1
			 * @a Desc a 2
			 */
			DOCBLOCK;
			$doc = new Doc($doc);
			$aTags = $doc->getTags('a');
			$bTags = $doc->getTags('b');
			$this->assertEquals(2, sizeof($aTags));
			$this->assertEquals(1, sizeof($bTags));
			$this->assertNull($doc->getTags('c'));
		}
	}
