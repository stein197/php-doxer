<?php
	namespace Stein197\Doxer;

	/**
	 * Class that represents docblock tags. usually it should not be used in code, it's used in the package internally.
	 * But anyway it could be used as follows:
	 * ```php
	 * (new Tag('@param string $name Description'))->getProperties()['type']; // 'string'
	 * ```
	 * @package Stein197\Doxer
	 */
	class Tag extends Parser {

		use Descriptable;

		// TODO: @example
		// TODO: @method [[static] return type] [name]([[type] [parameter]<, ...>]) [<description>]
		// TODO: @property[<-read|-write>] [Type] [name] [<description>]
		private const RULES = [
			// @author <name> <email>
			'author' => '/^(?<name>.+)?(?<email><.+>)?$/',
			// @deprecated [<version>]
			'deprecated' => '/^(?<version>.+?)?(?:\s|$)/',
			// @license [<url>] [<name>]
			'license' => '',
			// @link <url>
			'link' => '',
			// @param <type> <name>
			'param' => '',
			// @return <type>
			'return' => '',
			// @see <URI>
			'see' => '',
			// @since [<version>]
			'since' => '',
			// @var [<type>] [<name>]
			'var' => '',
			// @uses <URI>
			'uses' => '',
			// @throws <type>
			'throws' => ''
		];

		/** Name of the tag */
		private string $name;
		/** Tag's properties. Goes before description */
		private ?array $properties;

		/**
		 * Creates and parses
		 * @param string $doc 
		 * @return void 
		 */
		public function __construct(string $doc) {
			$this->parse($doc);
		}

		public function getName(): string {
			return $this->name;
		}

		public function getProperties(): ?array {
			return $this->properties;
		}

		protected function parse(string $doc): void {
			$matches = [];
			preg_match('/^@(.+?)\s(.+?)$/', $doc, $matches);
			$this->name = $matches[1];
			$this->description = $matches[2];
		}
	}
