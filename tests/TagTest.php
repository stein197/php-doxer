<?php
	namespace Stein197\Doxer;

	class TagTest extends BaseCase {

		/**
		 * @dataProvider data
		 */
		public function testTag(string $docblock, array $data): void {
			$tags = (new Doc($docblock))->getTags();
			if ($tags) {
				foreach ($tags as $i => $tag) {
					$curTagData = $data['tags'][$i];
					$this->assertEquals($curTagData['name'], $tag->getName());
					$this->assertEquals($curTagData['desc'], $tag->getDescription());
					if ($tag->getProperties()) {
						foreach ($tag->getProperties() as $name => $value) {
							$this->assertEquals($curTagData['props'][$name], $value);
						}
					}
				}
			} else {
				$this->markTestSkipped();
			}
		}


		public function testAuthor(): void {
			$this->markTestIncomplete();
		}

		public function testDeprecated(): void {
			$this->markTestIncomplete();
		}

		public function testLicense(): void {
			$this->markTestIncomplete();
		}

		public function testLink(): void {
			$this->markTestIncomplete();
		}

		public function testParam(): void {
			$this->markTestIncomplete();
		}

		public function testReturn(): void {
			$this->markTestIncomplete();
		}

		public function testSee(): void {
			$this->markTestIncomplete();
		}

		public function testSince(): void {
			$this->markTestIncomplete();
		}

		public function testVar(): void {
			$this->markTestIncomplete();
		}

		public function testUses(): void {
			$this->markTestIncomplete();
		}

		public function testThrows(): void {
			$this->markTestIncomplete();
		}
	}
