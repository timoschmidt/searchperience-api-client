<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemCollection;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueStatus;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestUrlQueueStatusBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\UrlQueueStatusBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'status/urlqueue';

	/**
	 * {@inheritdoc}
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function get() {
		$response = $this->getGetResponseFromEndpoint();
		return $this->buildUrlQueueStatusFromXml($response->xml());
	}

	/**
	 * @param string $xml
	 * @return UrlQueueStatus
	 */
	protected function buildUrlQueueStatusFromXml($xml) {
		$urlQueueStatus = new UrlQueueStatus();
		if(!$xml instanceof \SimpleXMLElement) {
			return $urlQueueStatus;
		}

		$urlQueueStatus->setWaitingCount((int) $xml->waitingCount);
		$urlQueueStatus->setProcessingCount((int) $xml->processingCount);
		$urlQueueStatus->setDeletedCount((int) $xml->deletedCount);
		$urlQueueStatus->setErrorCount((int) $xml->errorCount);
		$urlQueueStatus->setAllCount((int) $xml->allCount);

		return $urlQueueStatus;
	}
}