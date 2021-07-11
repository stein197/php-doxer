<?php
	namespace Stein197\Doxer;

	class Tag {

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

		private string $name;
		private ?string $description;
		private ?array $properties;

		public function __construct(string $doc) {
			$this->parse($doc);
		}

		public function getDescription(): ?string {
			return $this->description;
		}

		public function getName(): string {
			return $this->name;
		}

		public function getProperties(): ?array {
			return $this->properties;
		}

		private function parse(string $doc): void {
			$matches = [];
			preg_match('/^@(.+?)\s(.+?)$/', $doc, $matches);
			$this->name = $matches[1];
			$this->description = $matches[2];
		}
	}
