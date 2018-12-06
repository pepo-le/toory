<?php
App::uses('AutoLogin', 'Model');

/**
 * AutoLogin Test Case
 */
class AutoLoginTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.auto_login',
		'app.user',
		'app.task',
		'app.task_type',
		'app.reminder',
		'app.history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AutoLogin = ClassRegistry::init('AutoLogin');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AutoLogin);

		parent::tearDown();
	}

/**
 * testTokenRegistry method
 *
 * @return void
 */
	public function testTokenRegistry() {
		$this->markTestIncomplete('testTokenRegistry not implemented.');
	}

/**
 * testUserByLoginToken method
 *
 * @return void
 */
	public function testUserByLoginToken() {
		$this->markTestIncomplete('testUserByLoginToken not implemented.');
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
