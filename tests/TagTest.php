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

		public function testTag_withWrongSyntax_containsNulls(): void {
			$tag = (new Doc('/** @param a b c d */'))->getTags()[0];
			$this->assertNull($tag->getDescription());
			$this->assertNull($tag->getProperties());
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
				'Inline @author' => [
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
				'Multiline @author' => [
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
				'Inline @deprecated' => [
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
				'Multiline @deprecated' => [
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
				'Inline @license' => [
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
				'Multiline @license' => [
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
				'Inline @link' => [
					'/** @link LINK Description */',
					[
						[
							'name' => 'link',
							'desc' => 'Description',
							'props' => [
								'uri' => 'LINK'
							]
						]
					]
				],
				'Multiline @link' => [
					<<<DOC
					/**
					 * @link LINK
					 * @link LINK Description
					 */
					DOC,
					[
						[
							'name' => 'link',
							'desc' => null,
							'props' => [
								'uri' => 'LINK'
							]
						],
						[
							'name' => 'link',
							'desc' => 'Description',
							'props' => [
								'uri' => 'LINK'
							]
						]
					]
				],
				'Inline @param' => [
					'/** @param int|string $name Description */',
					[
						[
							'name' => 'param',
							'desc' => 'Description',
							'props' => [
								'type' => 'int|string',
								'name' => 'name'
							]
						]
					]
				],
				'Multiline @param' => [
					<<<DOC
					/**
					 * @param Type[] \$a
					 * @param Type[] \$a Description
					 * @param Type[] \$a Another
					 *                   description
					 */
					DOC,
					[
						[
							'name' => 'param',
							'desc' => null,
							'props' => [
								'type' => 'Type[]',
								'name' => 'a'
							]
						],
						[
							'name' => 'param',
							'desc' => 'Description',
							'props' => [
								'type' => 'Type[]',
								'name' => 'a'
							]
						],
						[
							'name' => 'param',
							'desc' => 'Another description',
							'props' => [
								'type' => 'Type[]',
								'name' => 'a'
							]
						]
					]
				],
				'Inline @return' => [
					'/** @return Type Desc */',
					[
						[
							'name' => 'return',
							'desc' => 'Desc',
							'props' => [
								'type' => 'Type'
							]
						]
					]
				],
				'Multiline @return' => [
					<<<DOC
					/**
					 * @return Type Another
					 *              description
					 * @return Type
					 */
					DOC,
					[
						[
							'name' => 'return',
							'desc' => 'Another description',
							'props' => [
								'type' => 'Type'
							]
						],
						[
							'name' => 'return',
							'desc' => null,
							'props' => [
								'type' => 'Type'
							]
						],
					]
				],
				'Inline @see' => [
					'/** @see URI Description */',
					[
						[
							'name' => 'see',
							'desc' => 'Description',
							'props' => [
								'uri' => 'URI'
							]
						],
					]
				],
				'Multiline @see' => [
					<<<DOC
					/**
					 * @see URI Description
					 * @see URI
					 */
					DOC,
					[
						[
							'name' => 'see',
							'desc' => 'Description',
							'props' => [
								'uri' => 'URI'
							]
						],
						[
							'name' => 'see',
							'desc' => null,
							'props' => [
								'uri' => 'URI'
							]
						],
					]
				],
				'Inline @since' => [
					'/** @since 1.0.0 Description */',
					[
						[
							'name' => 'since',
							'desc' => 'Description',
							'props' => [
								'version' => '1.0.0'
							]
						],
					]
				],
				'Multiline @since' => [
					<<<DOC
					/**
					 * @since 1.0.0
					 * @since 1.0.0 Description
					 */
					DOC,
					[
						[
							'name' => 'since',
							'desc' => null,
							'props' => [
								'version' => '1.0.0'
							]
						],
						[
							'name' => 'since',
							'desc' => 'Description',
							'props' => [
								'version' => '1.0.0'
							]
						],
					]
				],
				'Inline @var' => [
					'/** @var Type $name Description */',
					[
						[
							'name' => 'var',
							'desc' => 'Description',
							'props' => [
								'type' => 'Type',
								'name' => 'name'
							]
						],
					]
				],
				'Multiline @var' => [
					<<<DOC
					/**
					 * @var Type
					 * @var Type Description
					 * @var Type \$name
					 * @var Type \$name Description
					 */
					DOC,
					[
						[
							'name' => 'var',
							'desc' => null,
							'props' => [
								'type' => 'Type',
							]
						],
						[
							'name' => 'var',
							'desc' => 'Description',
							'props' => [
								'type' => 'Type',
							]
						],
						[
							'name' => 'var',
							'desc' => null,
							'props' => [
								'type' => 'Type',
								'name' => 'name'
							]
						],
						[
							'name' => 'var',
							'desc' => 'Description',
							'props' => [
								'type' => 'Type',
								'name' => 'name'
							]
						],
					]
				],
				'Inline @uses' => [
					'/** @uses URI Description */',
					[
						[
							'name' => 'uses',
							'desc' => 'Description',
							'props' => [
								'uri' => 'URI',
							]
						],
					]
				],
				'Multiline @uses' => [
					<<<DOC
					/**
					 * @uses URI Description
					 * @uses URI
					 */
					DOC,
					[
						[
							'name' => 'uses',
							'desc' => 'Description',
							'props' => [
								'uri' => 'URI',
							]
						],
						[
							'name' => 'uses',
							'desc' => null,
							'props' => [
								'uri' => 'URI',
							]
						],
					]
				],
				'Inline @throws' => [
					'/** @throws Type Desc */',
					[
						[
							'name' => 'throws',
							'desc' => 'Desc',
							'props' => [
								'type' => 'Type'
							]
						]
					]
				],
				'Multiline @throws' => [
					<<<DOC
					/**
					 * @throws Type Another
					 *              description
					 * @throws Type
					 */
					DOC,
					[
						[
							'name' => 'throws',
							'desc' => 'Another description',
							'props' => [
								'type' => 'Type'
							]
						],
						[
							'name' => 'throws',
							'desc' => null,
							'props' => [
								'type' => 'Type'
							]
						],
					]
				],
			];
		}
	}
