<?php

namespace Searchperience\Tests\Api\Client\Document\System\DateTime;

use Searchperience\Api\Client\System\DateTime\DateTimeService;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 * @date 05.03.14
 * @time 09:37
 */
class DateTimeServiceTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\DateTime\DateTimeService
	 */
	protected $dateTimeService;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->dateTimeService = new DateTimeService();
	}

	/**
	 * @return void
	 */
	public function tearDown() {

	}

	/**
	 * @return array
	 */
	public function dateTimeTestCaseDataProvider() {
		$dateOne = \DateTime::createFromFormat('Y-m-d H:i:s','2012-11-14 17:35:03', new \DateTimeZone('UTC'));
		$dateTwo = \DateTime::createFromFormat('Y-m-d H:i:s','2012-12-31 23:59:59', new \DateTimeZone('UTC'));

		return array(
			array(
				'string' => '2012-11-14 17:35:03',
				'dateTime' => $dateOne
			),
			array(
				'string' => '2012-12-31 23:59:59',
				'dateTime' => $dateTwo
			)
		);
	}

	/**
	 * @test
	 * @dataProvider dateTimeTestCaseDataProvider
	 */
	public function canConvertToDateString($stringDateTime, $dateTime) {
		$this->assertEquals($stringDateTime, $this->dateTimeService->getDateStringFromDateTime($dateTime));
	}

	/**
	 * @test
	 * @dataProvider dateTimeTestCaseDataProvider
	 */
	public function canConvertToDateTimeObject($stringDateTime, $dateTime) {
		$this->assertEquals($dateTime, $this->dateTimeService->getDateTimeFromApiDateString($stringDateTime));
	}

	/**
	 * @test
	 */
	public function canTakeTargetSystemTimeZoneAndDateFormatIntoAccount() {
			/** @var $dateTimeService DateTimeService */
		$dateTimeService = $this->getMock('Searchperience\Api\Client\System\DateTime\DateTimeService',array('getDateTimeFromFormat'),array(),'', false);
		$dateTimeService->setTargetSystemDateFormat('Y');
		$dateTimeService->setTargetSystemTimeZone(\DateTimeZone::AFRICA);
		$dateTimeService->expects($this->once())->method('getDateTimeFromFormat')->with('Y','2012-12-31 23:00:00',\DateTimeZone::AFRICA);
		$dateTimeService->getDateTimeFromApiDateString('2012-12-31 23:00:00');
	}
}