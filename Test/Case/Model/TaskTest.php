<?php
App::uses('Task', 'Model');

/**
 * Task Test Case
 */
class TaskTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.task',
		'app.user',
		'app.history',
		'app.auto_login',
		'app.reminder',
		'app.task_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Task = ClassRegistry::init('Task');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Task);

		parent::tearDown();
	}

/**
 * testCountRoutine method
 *
 * @return void
 */
	public function testタスク数を取得できる() {
        $this->assertGreaterThanOrEqual(1, $this->Task->countRoutine(1, date('Y-m-d')));
	}

/**
 * testTodayTask method
 *
 * @return void
 */
	public function test今日のタスクを取得できる() {
        $task = $this->Task->todayTask(1, date('Y-m-d'));
        $this->assertArrayHasKey('Task', $task[0]);
	}

/**
 * testFetchForwardTodo method
 *
 * @return void
 */
	public function testFetchForwardTodo() {
		$this->markTestIncomplete('testFetchForwardTodo not implemented.');
	}

/**
 * testFetchDoneTodo method
 *
 * @return void
 */
	public function testFetchDoneTodo() {
		$this->markTestIncomplete('testFetchDoneTodo not implemented.');
	}

/**
 * testFetchRoutine method
 *
 * @return void
 */
	public function testFetchRoutine() {
		$this->markTestIncomplete('testFetchRoutine not implemented.');
	}

/**
 * testCreateTask method
 *
 * @return void
 */
	public function test期限のないタスクを登録できる() {
        $data['Task'] = [
            'title' => 'あああ',
            'tasktype_id' => 1,
            'color' => '#aabbcc',
            'body' => 'あいうえおあいうえおあいうえお',
            'begin' => date('Y-m-d'),
            'expirecheck' => false,
            'expire' => '',
            'day' => [0,1,2,3,4,5,6]
        ];

        $result = $this->Task->createTask(1, $data);
        $this->assertArrayHasKey('Task', $result);
        $this->assertEquals('9999-12-31', $result['Task']['expire']);
	}

	public function test期限のあるタスクを登録できる() {
        $data['Task'] = [
            'title' => 'あああ',
            'tasktype_id' => 1,
            'color' => '#aabbcc',
            'body' => 'あいうえおあいうえおあいうえお',
            'begin' => date('Y-m-d'),
            'expirecheck' => true,
            'expire' => '2018-02-25',
            'day' => [0,1,2,3,4,5,6]
        ];

        $result = $this->Task->createTask(1, $data);
        $this->assertArrayHasKey('Task', $result);
        $this->assertEquals('2018-02-25', $result['Task']['expire']);
	}

/**
 * testDone method
 *
 * @return void
 */
	public function testTodoを完了できる() {
        $result = $this->Task->done('5ABdtXhSjmRnGpZDVte5zhaLZFyMr3EcYGyhXILO', 1, date('Y-m-d'));

        $this->assertTrue($result);
	}

/**
 * testFetchTask method
 *
 * @return void
 */
	public function testFetchTask() {
		$this->markTestIncomplete('testFetchTask not implemented.');
	}

/**
 * testEditTask method
 *
 * @return void
 */
	public function testタスクを編集できる() {
        $data['Task'] = [
            'title' => 'あああ',
            'color' => '#aabbcc',
            'body' => 'あいうえおあいうえおあいうえお',
            'status' => 0,
            'begin' => date('Y-m-d'),
            'expirecheck' => true,
            'expire' => '2018-02-25',
            'lastdone' => '',
            'day' => [0,1,2,3,4,5,6]
        ];

        $result = $this->Task->editTask('X579eWzkcJ1QL550QHPR1J0ePde31KAqhYhv1gN1', $data);
        $this->assertArrayHasKey('Task', $result);
        $this->assertEquals('2018-02-25', $result['Task']['expire']);
	}

/**
 * testCancel method
 *
 * @return void
 */
	public function testタスクの完了をキャンセルできる() {
        $result = $this->Task->cancel('5ABdtXhSjmRnGpZDVte5zhaLZFyMr3EcYGyhXILO');

        $this->assertTrue($result);
	}

}
