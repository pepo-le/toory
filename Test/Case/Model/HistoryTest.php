<?php
App::uses('History', 'Model');

/**
 * History Test Case
 */
class HistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->History = ClassRegistry::init('History');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->History);

		parent::tearDown();
	}

/**
 * testAddHistory method
 *
 * @return void
 */
	public function testAddHistory() {
		$this->markTestIncomplete('testAddHistory not implemented.');
	}

/**
 * testRollbackHistory method
 *
 * @return void
 */
	public function testRollbackHistory() {
		$this->markTestIncomplete('testRollbackHistory not implemented.');
	}

/**
 * testUpdateTotal method
 *
 * @return void
 */
	public function testUpdateTotal() {
		$this->markTestIncomplete('testUpdateTotal not implemented.');
	}

/**
 * testFetchAllHistories method
 *
 * @return void
 */
	public function testFetchAllHistories() {
		$this->markTestIncomplete('testFetchAllHistories not implemented.');
	}

/**
 * testFetchYearSum method
 *
 * @return void
 */
	public function testFetchYearSum() {
		$this->markTestIncomplete('testFetchYearSum not implemented.');
	}

/**
 * testFetchMonthSum method
 *
 * @return void
 */
	public function testFetchMonthSum() {
		$this->markTestIncomplete('testFetchMonthSum not implemented.');
	}

/**
 * testFetchWeekSum method
 *
 * @return void
 */
	public function testFetchWeekSum() {
		$this->markTestIncomplete('testFetchWeekSum not implemented.');
	}

/**
 * testMonthHistory method
 *
 * @return void
 */
	public function testMonthHistory() {
		$this->markTestIncomplete('testMonthHistory not implemented.');
	}

/**
 * testFetchHistory method
 *
 * @return void
 */
	public function testFetchHistory() {
		$this->markTestIncomplete('testFetchHistory not implemented.');
	}

/**
 * testEditHistory method
 *
 * @return void
 */
	public function testEditHistory() {
		$this->markTestIncomplete('testEditHistory not implemented.');
	}

}
