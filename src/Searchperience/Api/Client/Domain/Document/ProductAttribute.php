<?php


namespace Searchperience\Api\Client\Domain\Document;

use Searchperience\Common\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class ProductAttribute
 * @package Searchperience\Api\Client\Domain\Document
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class ProductAttribute {

	const TYPE_TEXT = "text";

	const TYPE_DATE = "date";

	const TYPE_STRING = "string";

	const TYPE_FLOAT = "float";

	/**
	 * @var array
	 */
	protected static $allowedTypes = array(
		self::TYPE_DATE,
		self::TYPE_FLOAT,
		self::TYPE_STRING,
		self::TYPE_TEXT
	);

	/**
	 * @var string
	 */
	protected $type = 'string';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var bool
	 */
	protected $forSearching = false;

	/**
	 * @var bool
	 */
	protected $forFaceting = false;

	/**
	 * @var bool
	 */
	protected $forSorting = false;

	/**
	 * @var string
	 */
	protected $language = '';

	/**
	 * @var string
	 */
	protected $storedId = '';

	/**
	 * @return boolean
	 */
	public function getForFaceting() {
		return $this->forFaceting;
	}

	/**
	 * @param boolean $forFaceting
	 */
	public function setForFaceting($forFaceting) {
		$this->forFaceting = $forFaceting;
	}

	/**
	 * @return boolean
	 */
	public function getForSearching() {
		return $this->forSearching;
	}

	/**
	 * @param boolean $forSearching
	 */
	public function setForSearching($forSearching) {
		$this->forSearching = $forSearching;
	}

	/**
	 * @return boolean
	 */
	public function getForSorting() {
		return $this->forSorting;
	}

	/**
	 * @param boolean $forSorting
	 */
	public function setForSorting($forSorting) {
		$this->forSorting = $forSorting;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * @param $value
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function addValue($value) {
		if(!is_integer($value) && !is_float($value) && !is_string($value)) {
			throw new InvalidArgumentException("Value needs to be a float, integer or string provided: ".serialize($value));
		}
		$this->values[] = $value;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
}