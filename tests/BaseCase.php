<?php
	namespace Stein197\Doxer;

	use PHPUnit\Framework\TestCase;

	class BaseCase extends TestCase {

		public final function data(): array {
			return [
				'Empty' => [
					'/**  */',
					['desc' => null,
					'tags' => null]
				],
				'One-lined description' => [
					'/** Description */',
					['desc' => 'Description',
					'tags' => null]
				],
				'Multilined one-line description' => [
					<<<DOC
					/**
					 * Description
					 */
					DOC,
					['desc' => 'Description',
					'tags' => null]
				],
				'Multilined description' => [
					<<<DOC
					/**
					 * Description.
					 * Another string
					 * with new line.
					 */
					DOC,
					['desc' => 'Description. Another string with new line.',
					'tags' => null]
				],
				'One-lined single tag' => [
					'/** @tag */',
					[
						'desc' => null,
						'tags' => [
							[
								'name' => 'tag',
								'desc' => null
							]
						]
					]
				],
				'Multilined single tag' => [
					<<<DOC
					/**
					 * @tag
					 */
					DOC,
					[
						'desc' => null,
						'tags' => [
							[
								'name' => 'tag',
								'desc' => null
							]
						]
					]
				],
				'Multilined single tag with description' => [
					<<<DOC
					/**
					 * @tag Description
					 */
					DOC,
					[
						'desc' => null,
						'tags' => [
							[
								'name' => 'tag',
								'desc' => 'Description'
							]
						]
					]
				],
				'Multilined single tag with multilined description' => [
					<<<DOC
					/**
					 * @tag Description
					 * another line
					 *     another one.
					 */
					DOC,
					[
						'desc' => null,
						'tags' => [
							[
								'name' => 'tag',
								'desc' => 'Description another line another one.'
							]
						]
					]
				],
				'Description & tag' => [
					<<<DOC
					/**
					 * Description
					 * @tag Description
					 */
					DOC,
					[
						'desc' => 'Description',
						'tags' => [
							[
								'name' => 'tag',
								'desc' => 'Description'
							]
						]
					]
				],
				'Multiple tags' => [
					<<<DOC
					/**
					 * @tag Desc1
					 * @tag Desc2
					 */
					DOC,
					[
						'desc' => null,
						'tags' => [
							[
								'name' => 'tag',
								'desc' => 'Desc1'
							],
							[
								'name' => 'tag',
								'desc' => 'Desc2'
							]
						]
					]
				]
			];
		}
	}
