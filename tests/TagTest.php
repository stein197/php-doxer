<?php
	namespace Stein197\Doxer;

use PHPUnit\Framework\IncompleteTestError;

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


		/**
		 * @dataProvider dataRegisteredTags
		 */
		public function testRegisteredTags(string $doc, array $expectedTags): void {
			$tags = (new Doc($doc))->getTags();
			foreach ($tags as $i => $tag) {
				$expectedTag = $expectedTags[$i];
				$this->assertEquals($expectedTag['name'], $tag->getName());
				$this->assertEquals($expectedTag['desc'], $tag->getDescription());
				$this->assertEquals($expectedTag['props'], $tag->getProperties());
			}
		}

		public function dataRegisteredTags(): array {
			return [
				// @author
				[
					'/** @author Name Surname <email> */',
					[
						[
							'name' => 'author',
							'desc' => null,
							'props' => [
								'name' => 'Name Surname',
								'email' => 'email'
							]
						]
					]
				],
				[
					<<<DOC
					/**
					 * @author Name Surname <email>
					 * @author Name Surname
					 */
					DOC,
					[
						[
							'name' => 'author',
							'desc' => null,
							'props' => [
								'name' => 'Name Surname',
								'email' => 'email'
							]
						],
						[
							'name' => 'author',
							'desc' => null,
							'props' => [
								'name' => 'Name Surname',
							]
						]
					]
				],
				// @deprecated
				[
					'/** @deprecated 1.2-beta Description */',
					[
						[
							'name' => 'deprecated',
							'desc' => 'Description',
							'props' => [
								'version' => '1.2-beta'
							]
						]
					]
				],
				[
					<<<DOC
					/**
					 * @deprecated
					 * @deprecated 1.0.0
					 * @deprecated 1.0.0 Description
					 */
					DOC,
					[
						[
							'name' => 'deprecated',
							'desc' => null,
							'props' => null
						],
						[
							'name' => 'deprecated',
							'desc' => null,
							'props' => [
								'version' => '1.0.0'
							]
						],
						[
							'name' => 'deprecated',
							'desc' => 'Description',
							'props' => [
								'version' => '1.0.0'
							]
						],
					]
				],
				// @license
				[
					'/** @license https://domain.com GPL */',
					[
						[
							'name' => 'license',
							'desc' => 'GPL',
							'props' => [
								'url' => 'https://domain.com'
							]
						]
					]
				],
				[
					<<<DOC
					/**
					 * @license GPL
					 * @license https://domain.com
					 * @license https://domain.com GPL
					 */
					DOC,
					[
						[
							'name' => 'license',
							'desc' => 'GPL',
							'props' => null
						],
						[
							'name' => 'license',
							'desc' => null,
							'props' => [
								'url' => 'https://domain.com'
							]
						],
						[
							'name' => 'license',
							'desc' => 'GPL',
							'props' => [
								'url' => 'https://domain.com'
							]
						],
					]
				],
			];
		}
	}
