<?php
	namespace Stein197\Doxer;

	trait Descriptable {

		protected ?string $description = null;

		/**
		 * Returns comment description
		 * @return null|string Description
		 */
		public function getDescription(): ?string {
			return $this->description;
		}
	}
