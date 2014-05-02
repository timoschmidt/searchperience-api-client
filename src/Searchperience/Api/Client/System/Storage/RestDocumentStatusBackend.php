<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\Document\DocumentCollection;
use Searchperience\Api\Client\Domain\Document\DocumentStatus;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestDocumentStatusBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\DocumentStatusBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'status/documentsstatus';

	/**
	 * {@inheritdoc}
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function get() {
		$xml = $this->getStatusXmlFromEndpoint();
		return $this->buildUrlQueueStatusFromXml($xml);
	}

	/**
	 * @return \SimpleXMLElement
	 * @throws \Guzzle\Common\Exception\InvalidArgumentException
	 * @throws \Guzzle\Common\Exception\RuntimeException
	 */
	protected function getStatusXmlFromEndpoint() {
		/** @var $response \Guzzle\http\Message\Response */
		$response = $this->getGetResponseFromEndpoint();
		return $response->xml();
	}

	/**
	 * @param $xml
	 * @return DocumentStatus
	 */
	protected function buildUrlQueueStatusFromXml($xml) {
		$documentStatus = new DocumentStatus();
		if(!$xml instanceof \SimpleXMLElement) {
			return $documentStatus;
		}

		$documentStatus->setWaitingCount((int) $xml->waitingCount);
		$documentStatus->setProcessingCount((int) $xml->processingCount);
		$documentStatus->setDeletedCount((int) $xml->deletedCount);
		$documentStatus->setErrorCount((int) $xml->errorCount);
		$documentStatus->setAllCount((int) $xml->allCount);
		$documentStatus->setProcessedCount((int) $xml->processedCount);

		return $documentStatus;
	}
}