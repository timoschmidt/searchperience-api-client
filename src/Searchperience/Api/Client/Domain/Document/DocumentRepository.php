<?php

namespace Searchperience\Api\Client\Domain\Document;

use Searchperience\Api\Client\Domain\Document\Filters\FiltersCollection;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;

/**
 * @author Michael Klapper <michael.klapper@aoe.com>
 */
class DocumentRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\DocumentBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $documentValidator;

	/**
	 * @var \Searchperience\Api\Client\Domain\Document\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\DocumentBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\DocumentBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $documentValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $documentValidator) {
		$this->documentValidator = $documentValidator;
	}

	/**
	 * Injects the filter collection factory
	 *
	 * @param \Searchperience\Api\Client\Domain\Document\Filters\FilterCollectionFactory $filterCollectionFactory
	 * @return void
	 */
	public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\Document\Filters\FilterCollectionFactory $filterCollectionFactory) {
		$this->filterCollectionFactory = $filterCollectionFactory;
	}

	/**
	 * Add a new Document to the index
	 *
	 * @param \Searchperience\Api\Client\Domain\Document\AbstractDocument $document
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return integer HTTP Status code
	 */
	public function add(\Searchperience\Api\Client\Domain\Document\AbstractDocument $document) {
		$violations = $this->documentValidator->validate($document);

		if ($violations->count() > 0) {
			throw new InvalidArgumentException('Given object of type "' . get_class($document) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($document);
		return $status;
	}

	/**
	 * Get a Document by foreignId
	 *
	 * The foreignId can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $foreignId
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document\AbstractDocument $document
	 */
	public function getByForeignId($foreignId) {
		if (!is_string($foreignId) && !is_integer($foreignId) || preg_match('/^[a-zA-Z0-9_-]*$/u', $foreignId) !== 1) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $foreignId. Input was: ' . serialize($foreignId));
		}

		$document = $this->checkTypeAndDecorate($this->storageBackend->getByForeignId($foreignId));
		return $document;
	}

	/**
	 * Get a Document by id
	 *
	 * The id is the internal technical id
	 * 0-9:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $id
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document\AbstractDocument $document
	 */
	public function getById($id) {
		if (!is_numeric($id)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $id. Input was: ' . serialize($id));
		}

		$document = $this->checkTypeAndDecorate($this->storageBackend->getById($id));
		return $document;
	}

	/**
	 * Get a Document by url
	 *
	 * The url can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $url
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document\AbstractDocument $document
	 */
	public function getByUrl($url) {
		if (!is_string($url) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $url. Input was: ' . serialize($url));
		}

		$document = $this->checkTypeAndDecorate($this->storageBackend->getByUrl($url));
		return $document;
	}

	/**
	 * Get all documents by source
	 *
	 * The source can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param int $start
	 * @param int $limit
	 * @param string $source
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentCollection
	 * @deprecated Please now use getAllByFilters with a filter arguments array or getAllByFilterCollection with a proper FilterCollection
	 */
	public function getAll($start = 0, $limit = 10, $source = '', $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (isset($source) && (!is_string($source) && !is_integer($source) || preg_match('/^[a-zA-Z0-9_-]*$/u', $source) !== 1)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $url. Input was: ' . serialize($source));
		}
		if ( !is_integer($start) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$filterCollection = $this->filterCollectionFactory->createFromFilterArguments(
				array('source' => array('source' => $source))
		);

		return $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);
	}


	/**
	 * Method to retrieve all documents by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentCollection
	 */
	public function getAllByFilters($start = 0, $limit = 10, array $filterArguments = array(), $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC){
		if ( !is_integer($start) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_array($filterArguments)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $filterArguments. Input was: ' . serialize($filterArguments));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$filterCollection = $this->filterCollectionFactory->createFromFilterArguments($filterArguments);
		$documents = $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);

		return $documents;
	}

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField
	 * @param string $sortingType
	 * @return DocumentCollection
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection= null, $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$documents = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);
		return $this->decorateDocuments($documents);
	}

	/**
	 * Delete a Document by foreignId
	 *
	 * The foreignId can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $foreignId
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return integer HTTP status code
	 */
	public function deleteByForeignId($foreignId) {
		if (!is_string($foreignId) && !is_integer($foreignId) || preg_match('/^[a-zA-Z0-9_-]*$/u', $foreignId) !== 1) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $foreignId. Input was: ' . serialize($foreignId));
		}

		$statusCode = $this->storageBackend->deleteByForeignId($foreignId);
		return $statusCode;
	}

	/**
	 * Delete a Document by id (internal technical id of a document)
	 *
	 * The id can be a integer of:
	 * 0-9:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $id
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return integer HTTP status code
	 */
	public function deleteById($id) {
		if (!is_numeric($id) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integers values as $id. Input was: ' . serialize($id));
		}

		$statusCode = $this->storageBackend->deleteById($id);
		return $statusCode;
	}

	/**
	 * Delete Document by source
	 *
	 * The source can be a string of:
	 * 0-9a-zA-Z_-.:
	 *
	 * @param string $source
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Exception\DocumentNotFoundException
	 * @return void
	 */
	public function deleteBySource($source) {
		if (!is_string($source)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $source. Input was: ' . serialize($source));
		}

		return $this->storageBackend->deleteBySource($source);
	}

	/**
	 * @param DocumentCollection $documents
	 * @return DocumentCollection
	 */
	private function decorateDocuments(DocumentCollection $documents) {
		$newCollection = new DocumentCollection();
		$newCollection->setTotalCount($documents->getTotalCount());
		foreach ($documents as $document) {
			$newCollection->append($this->checkTypeAndDecorate($document));
		}
		return $newCollection;
	}

	/**
	 * Checks the type and decorates it if it is a document.
	 *
	 * @param mixed $document
	 * @return mixed
	 */
	protected function checkTypeAndDecorate($document) {
		if($document instanceof AbstractDocument) {
			return $this->decorateDocument($document);
		}

		return $document;
	}

	/**
	 * Extend the class and override this method:
	 *
	 * This method gives you the possibility to decorate the document object
	 *
	 * @param AbstractDocument $document
	 * @return AbstractDocument
	 */
	protected function decorateDocument(AbstractDocument $document) {
		return $document;
	}
}
