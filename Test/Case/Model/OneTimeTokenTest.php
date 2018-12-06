<?php
App::uses('OneTimeToken', 'Model');

/**
 * OneTimeToken Test Case
 */
class OneTimeTokenTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.one_time_token',
		'app.user',
		'app.task',
		'app.task_type',
		'app.reminder',
		'app.history',
		'app.auto_login'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OneTimeToken = ClassRegistry::init('OneTimeToken');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OneTimeToken);

		parent::tearDown();
	}

/**
 * testAddToken method
 *
 * @return void
 */
	public function testAddToken() {
		$this->markTestIncomplete('testAddToken not implemented.');
	}

/**
 * testFetchTokenByUserId method
 *
 * @return void
 */
	public function testFetchTokenByUserId() {
		$this->markTestIncomplete('testFetchTokenByUserId not implemented.');
	}

/**
 * testFetchTokenByToken method
 *
 * @return void
 */
	public function testFetchTokenByToken() {
		$this->markTestIncomplete('testFetchTokenByToken not implemented.');
	}

/**
 * testDeleteToken method
 *
 * @return void
 */
	public function testDeleteToken() {
		$this->markTestIncomplete('testDeleteToken not implemented.');
	}

}
