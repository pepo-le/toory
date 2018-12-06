<?php
App::uses('Reminder', 'Model');

/**
 * Reminder Test Case
 */
class ReminderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.reminder',
		'app.task',
		'app.user',
		'app.history',
		'app.auto_login',
		'app.task_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Reminder = ClassRegistry::init('Reminder');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Reminder);

		parent::tearDown();
	}

/**
 * testAddTimeReminder method
 *
 * @return void
 */
	public function testAddTimeReminder() {
		$this->markTestIncomplete('testAddTimeReminder not implemented.');
	}

/**
 * testAddDatetimeReminder method
 *
 * @return void
 */
	public function testAddDatetimeReminder() {
		$this->markTestIncomplete('testAddDatetimeReminder not implemented.');
	}

/**
 * testEditTimeReminder method
 *
 * @return void
 */
	public function testEditTimeReminder() {
		$this->markTestIncomplete('testEditTimeReminder not implemented.');
	}

/**
 * testEditDatetimeReminder method
 *
 * @return void
 */
	public function testEditDatetimeReminder() {
		$this->markTestIncomplete('testEditDatetimeReminder not implemented.');
	}

/**
 * testCheckReminderLimit method
 *
 * @return void
 */
	public function testCheckReminderLimit() {
		$this->markTestIncomplete('testCheckReminderLimit not implemented.');
	}

/**
 * testFetchReminder method
 *
 * @return void
 */
	public function testFetchReminder() {
		$this->markTestIncomplete('testFetchReminder not implemented.');
	}

/**
 * testFetchAllReminder method
 *
 * @return void
 */
	public function testFetchAllReminder() {
		$this->markTestIncomplete('testFetchAllReminder not implemented.');
	}

/**
 * testStopReminder method
 *
 * @return void
 */
	public function testStopReminder() {
		$this->markTestIncomplete('testStopReminder not implemented.');
	}

}
