<?php
namespace Stein197\Doxer;

/**
 * Use this trait when class has a description.
 */
trait Descriptable {

	private ?string $description = null;

	/**
	 * Returns description.
	 * @return null|string Description.
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
}
