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
			'author' => '/^(?<name>.+?)(?:\s+<(?<email>.+)>)?$/', // @author <name> [<email>]
			'deprecated' => '/^(?<version>\d[^\s]+)?(?:\s*(?<description>.+))?$/', // @deprecated [<version>]
			'license' => '/^(?<url>(?:[a-zA-Z]+:)?\/\/[^\s]+)?(?:\s*(?<description>.+))?$/', // @license [<url>]
			'link' => '/^(?<uri>[^\s]+)(?:\s*(?<description>.+))?$/', // @link <uri>
			'param' => '/^(?<type>.+?)\s+\$(?<name>.+?)(?:\s+(?<description>.+))?$/', // @param <type> <name>
			'return' => '/^(?<type>.+?)(?:\s+(?<description>.+))?$/', // @return <type>
			'see' => '/^(?<uri>[^\s]+)(?:\s*(?<description>.+))?$/', // @see <URI>
			'since' => '/^(?<version>\d[^\s]+)(?:\s*(?<description>.+))?$/', // @since <version>
			'var' => '/^(?<type>[^\s]+)(?:\s+\$(?<name>[^\s]+))?(?:\s+(?<description>.+))?/', // @var <type> [<name>]
			'uses' => '/^(?<uri>[^\s]+)(?:\s*(?<description>.+))?$/', // @uses <URI>
			'throws' => '/^(?<type>.+?)(?:\s+(?<description>.+))?$/' // @throws <type>
		];

		/** Name of the tag */
		private string $name;
		/** Tag's properties. Goes before description */
		private ?array $properties = null;

		/**
		 * Creates and parses tag lines. For example "@tag prop1 prop2 desc" can be parsed.
		 * @param string $doc Tag string to parse.
		 */
		public function __construct(string $doc) {
			$this->parse($doc);
		}

		/**
		 * Returns the name of the tag.
		 * @return string Name of the tag
		 */
		public function getName(): string {
			return $this->name;
		}

		/**
		 * Retuens tag's properties. Only registered tags can have properties. For example for `@param` tag there are
		 * two properties - `type` of the param and optionally the `name`.
		 * @return null|array Tag's properties.
		 */
		public function getProperties(): ?array {
			return $this->properties;
		}

		protected function parse(string $doc): void {
			$parts = preg_split('/\s+/', ltrim($doc, '@'), 2);
			$this->name = $parts[0];
			if (!isset($parts[1]))
				return;
			$rest = $parts[1];
			if (isset(self::RULES[$this->name])) {
				$matches = [];
				if (preg_match(self::RULES[$this->name], $rest, $matches))
					foreach ($matches as $key => $value)
						if (is_string($key)) {
							if ($key === 'description') {
								$this->description = $value;
							} else {
								if ($this->properties === null)
									$this->properties = [];
								$this->properties[$key] = $value;
							}
						}
			} else {
				$this->description = $rest;
			}
		}
	}
