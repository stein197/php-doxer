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
	}
