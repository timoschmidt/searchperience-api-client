<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Interface UrlqueueBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
interface UrlQueueItemBackendInterface extends BackendInterface {

	/**
	 * @param \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $document
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post(\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlqueue);

	/**
	 * @param string $documentId
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItem
	 */
	public function getByDocumentId($documentId);

	/**
	 * @param string $url
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItem
	 */
	public function getByUrl($url);

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItemCollection
	 * @return mixed
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection = null);
}
