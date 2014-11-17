<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Michael Klapper <michael.klapper@aoe.com>
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
abstract class AbstractDocument extends AbstractEntity {

	/**
	 * Indicates that this document is marked for deletion.
	 *
	 * @var string
	 */
	const IS_DELETING = 'IS_DELETING';

	/**
	 * Indicates that this document is marked as duplicate of another document.
	 *
	 *
	 * @var string
	 */
	const IS_DUPLICATE = 'IS_DUPLICATE';

	/**
	 * Indicates that this document has an error because it could not crawled (see "errorCount" property).
	 *
	 * @var string
	 */
	const IS_ERROR = 'IS_ERROR';

	/**
	 * Indicates that this document is marked as to be processed by one of the current indexer processes.
	 *
	 * @var string
	 */
	const IS_PROCESSING = 'IS_PROCESSING';

	/**
	 * Indicates that the document is marked as a redirect.
	 *
	 * @var string
	 */
	const IS_REDIRECT = 'IS_REDIRECT';

	/**
	 * Indicates that the document is waiting to be processed.
	 *
	 * @var string
	 */
	const IS_WAITING = 'IS_WAITING';

	/**
	 * @var array
	 */
	protected static $validNotifications = array(
		self::IS_DELETING,
		self::IS_DUPLICATE,
		self::IS_ERROR,
		self::IS_PROCESSING,
		self::IS_REDIRECT,
		self::IS_WAITING
	);

	/**
	 * @var integer
	 */
	const INDEX_PRIORITY_NORMAL 	= 0;

	/**
	 * @var integer
	 */

	const INDEX_PRIORITY_HIGH 		= 1;

	/**
	 * @var integer
	 */
	const INDEX_PRIORITY_IMMEDIATE 	= 2;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var \DateTime
	 */
	protected $lastProcessingDate = null;

	/**
	 * @var \DateTime
	 */
	protected $lastCrawlingDateTime = null;

	/**
	 * @var int
	 */
	protected $boostFactor;

	/**
	 * @var int
	 */
	protected $isProminent;

	/**
	 * Schedule a document for (re-)processing
	 *
	 * @var int
	 */
	protected $isMarkedForProcessing;

	/**
	 * Schedule a document for deleting
	 *
	 * @var int
	 */
	protected $isMarkedForDeletion;

	/**
	 * Can be used to remove a document from the index
	 *
	 * @var int
	 */
	protected $noIndex;

	/**
	 * @var string
	 * @Assert\Length(min = 1, max = 255)
	 */
	protected $foreignId;

	/**
	 * @var string
	 * @Assert\Url
	 * @Assert\Length(max = 1000)
	 */
	protected $url;

	/**
	 * @var string
	 * @Assert\Length(min = 3, max = 40)
	 * @Assert\NotBlank
	 */
	protected $source;

	/**
	 * @var string
	 * @Assert\Length(min = 3, max = 128)
	 * @Assert\NotBlank
	 */
	protected $mimeType;

	/**
	 * Content can be set up to 3MB
	 *
	 * @var string
	 * @Assert\Length(max = 3145728)
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $generalPriority;

	/**
	 * @var string
	 */
	protected $temporaryPriority;

	/**
	 * @var integer
	 */
	protected $errorCount;

	/**
	 * @var string
	 */
	protected $lastErrorMessage;

	/**
	 * @var integer
	 */
	protected $isRedirectTo;

	/**
	 * @var integer
	 */
	protected $isDuplicateOf;

	/**
	 * @var string
	 */
	protected $recrawlTimeSpan;

	/**
	 * @var int
	 */
	protected $internalNoIndex;

	/**
	 * @var float
	 */
	protected $pageRank;

	/**
	 * @var string
	 */
	protected $solrCoreHints;

	/**
	 * @var \DOMDocument
	 */
	protected $contentDOM = null;

	/**
	 * @return array
	 */
	public static function getValidNotifications() {
		return self::$validNotifications;
	}


	/**
	 * @param float $pageRank
	 */
	public function setPageRank($pageRank) {
		$this->pageRank = $pageRank;
	}

	/**
	 * @return float
	 */
	public function getPageRank() {
		return $this->pageRank;
	}

	/**
	 * @param string $solrCoreHints
	 */
	public function setSolrCoreHints($solrCoreHints) {
		$this->solrCoreHints = $solrCoreHints;
	}

	/**
	 * @return string
	 */
	public function getSolrCoreHints() {
		return $this->solrCoreHints;
	}

	/**
	 * @param string $recrawlTimeSpan
	 */
	public function setRecrawlTimeSpan($recrawlTimeSpan) {
		$this->recrawlTimeSpan = $recrawlTimeSpan;
	}

	/**
	 * @return string
	 */
	public function getRecrawlTimeSpan() {
		return $this->recrawlTimeSpan;
	}

	/**
	 * @param int $internalNoIndex
	 */
	public function setInternalNoIndex($internalNoIndex) {
		$this->internalNoIndex = $internalNoIndex;
	}

	/**
	 * @return int
	 */
	public function getInternalNoIndex() {
		return $this->internalNoIndex;
	}

	/**
	 * @param int $errorCount
	 */
	public function setErrorCount($errorCount) {
		$this->errorCount = $errorCount;
	}

	/**
	 * @return int
	 */
	public function getErrorCount() {
		return $this->errorCount;
	}

	/**
	 * @param int $isDuplicateOf
	 */
	public function setIsDuplicateOf($isDuplicateOf) {
		$this->isDuplicateOf = $isDuplicateOf;
	}

	/**
	 * @return int
	 */
	public function getIsDuplicateOf() {
		return $this->isDuplicateOf;
	}

	/**
	 * @param int $isRedirectTo
	 */
	public function setIsRedirectTo($isRedirectTo) {
		$this->isRedirectTo = $isRedirectTo;
	}

	/**
	 * @return int
	 */
	public function getIsRedirectTo() {
		return $this->isRedirectTo;
	}

	/**
	 * @param string $lastErrorMessage
	 */
	public function setLastErrorMessage($lastErrorMessage) {
		$this->lastErrorMessage = $lastErrorMessage;
	}

	/**
	 * @return string
	 */
	public function getLastErrorMessage() {
		return $this->lastErrorMessage;
	}

	/**
	 * @param string $lastProcessing
	 * @deprecated Please now use setLastProcessingDate
	 */
	public function setLastProcessing($lastProcessing) {
		$this->lastProcessingDate  = \DateTime::createFromFormat('Y-m-d H:i:s',$lastProcessing,new \DateTimeZone('UTC'));
	}

	/**
	 * @return string
	 * @deprecated Please now use getLastProcessingDate
	 */
	public function getLastProcessing() {
		return ($this->lastProcessingDate instanceof \DateTime) ?  $this->lastProcessingDate->format('Y-m-d H:i:s') : '';
	}

	/**
	 * @param \DateTime $lastProcessingDate
	 * @return \DateTime
	 */
	public function setLastProcessingDate(\DateTime $lastProcessingDate) {
		return $this->lastProcessingDate = $lastProcessingDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastProcessingDate() {
		return $this->lastProcessingDate;
	}

	/**
	 * @param \DateTime $dateTime
	 * @return \DateTime
	 */
	public function setLastCrawlingDateTime(\DateTime $dateTime) {
		return $this->lastCrawlingDateTime = $dateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastCrawlingDateTime() {
		return $this->lastCrawlingDateTime;
	}

	/**
	 * @param int $boostFactor
	 */
	public function setBoostFactor($boostFactor) {
		$this->boostFactor = $boostFactor;
	}

	/**
	 * @return int
	 */
	public function getBoostFactor() {
		return $this->boostFactor;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $isMarkedForProcessing
	 */
	public function setIsMarkedForProcessing($isMarkedForProcessing) {
		$this->isMarkedForProcessing = $isMarkedForProcessing;
	}

	/**
	 * @return int
	 */
	public function getIsMarkedForProcessing() {
		return $this->isMarkedForProcessing;
	}


	/**
	 * @param int $isMarkedForDeletion
	 */
	public function setIsMarkedForDeletion($isMarkedForDeletion) {
		$this->isMarkedForDeletion = $isMarkedForDeletion;
	}

	/**
	 * @return int
	 */
	public function getIsMarkedForDeletion() {
		return $this->isMarkedForDeletion;
	}

	/**
	 * @param int $isProminent
	 */
	public function setIsProminent($isProminent) {
		$this->isProminent = $isProminent;
	}

	/**
	 * @return int
	 */
	public function getIsProminent() {
		return $this->isProminent;
	}

	/**
	 * @param int $noIndex
	 */
	public function setNoIndex($noIndex) {
		$this->noIndex = $noIndex;
	}

	/**
	 * @return int
	 */
	public function getNoIndex() {
		return $this->noIndex;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * The foreignId can be a string of:
	 * 0-9a-zA-Z_-.:
	 *
	 * @param string $foreignId
	 */
	public function setForeignId($foreignId) {
		$this->foreignId = $foreignId;
	}

	/**
	 * @return string
	 */
	public function getForeignId() {
		return $this->foreignId;
	}

	/**
	 * @param string $generalPriority
	 */
	public function setGeneralPriority($generalPriority) {
		$this->generalPriority = $generalPriority;
	}

	/**
	 * @return string
	 */
	public function getGeneralPriority() {
		return $this->generalPriority;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @param string $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param string $temporaryPriority
	 */
	public function setTemporaryPriority($temporaryPriority) {
		$this->temporaryPriority = $temporaryPriority;
	}

	/**
	 * @return string
	 */
	public function getTemporaryPriority() {
		return $this->temporaryPriority;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Retrieve the state of a current document to easily indicate if a document:
	 * - has an error
	 * - is duplicate of
	 * - is currently in processing
	 * - is marked for deletion
	 *
	 * @return array
	 */
	public function getNotifications() {
		$notifications = array();

		if (!is_null($this->errorCount) && $this->errorCount > 0) {
			$notifications[] = Document::IS_ERROR;
		}

		if (!is_null($this->isDuplicateOf) && $this->isDuplicateOf > 0) {
			$notifications[] = Document::IS_DUPLICATE;
		}

		if (!is_null($this->isMarkedForProcessing) && $this->isMarkedForProcessing > 0) {
			$notifications[] = Document::IS_PROCESSING;
		}

		if (!is_null($this->isMarkedForDeletion) && $this->isMarkedForDeletion > 0) {
			$notifications[] = Document::IS_DELETING;
		}

		return array_unique($notifications);
	}

	/**
	 * @param $xpath
	 * @param $query
	 * @return string
	 */
	protected function getFirstNodeContent($xpath, $query) {
		$nodes = $xpath->query($query);
		if (!$nodes->length == 1) {
			return "";
		}

		return (string)$nodes->item(0)->textContent;
	}

	/**
	 * @return \DOMDocument
	 */
	public function getContentDOM() {
		$this->contentDOM = new \DOMDocument('1.0','UTF-8');
		$this->contentDOM->loadXML($this->content);

		return $this->contentDOM;
	}
}
