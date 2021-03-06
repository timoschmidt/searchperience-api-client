<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractDateFilter;

/**
 * Class CrawlFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CrawlFilter extends AbstractDateFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var \DateTime $crawlStart
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $crawlStart;

	/**
	 * @var \DateTime $crawlEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $crawlEnd;

	/**
	 * @param \DateTime  $crawlEnd
	 */
	public function setCrawlEnd($crawlEnd) {
		$this->crawlEnd = $crawlEnd;
	}

	/**
	 * @return \DateTime
	 */
	public function getCrawlEnd() {
		return $this->crawlEnd;
	}

	/**
	 * @param \DateTime $crawlStart
	 */
	public function setCrawlStart($crawlStart) {
		$this->crawlStart = $crawlStart;
	}

	/**
	 * @return \DateTime
	 */
	public function getCrawlStart() {
		return $this->crawlStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if(!empty($this->crawlStart)) {
			$this->filterString = sprintf("&crawlStart=%s", rawurlencode($this->toString($this->getCrawlStart())));
		}
		if (!empty($this->crawlEnd)) {
			$this->filterString .= sprintf("&crawlEnd=%s", rawurlencode($this->toString($this->getCrawlEnd())));
		}
		return $this->filterString;
	}
}