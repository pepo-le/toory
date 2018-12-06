<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 */
class UsersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.task',
		'app.task_type',
		'app.reminder',
		'app.history',
		'app.auto_login',
		'app.one_time_token'
	);

    private $users;

    public function setUp() {
        $Users = $this->generate('Users', array(
            'components' => array(
                'Session',
                'Auth' => array('user', 'login', 'loggedIn', 'redirect')
            ),
        ));
        $Users->Auth
            ->expects($this->any())
            ->method('loggedIn')
            ->will($this->returnValue(false));
        $Users->Auth
            ->expects($this->any())
            ->method('login')
            ->will($this->returnValue(true));
        $Users->Auth
            ->expects($this->any())
            ->method('redirect')
            ->will($this->returnValue('/tasks'));
        $Users->Auth
            ->staticExpects($this->any()) // なぜかstaticで呼ばないと駄目
            ->method('user')
            // ->with('id') なぜか引数が認識されない
            ->will($this->returnValue(1));
    }

/**
 * testSignup method
 *
 * @return void
 */
	public function testログアウトできる() {
        $this->testAction('/users/logout', ['method' => 'get']);
        $this->assertRegExp('/login$/', $this->headers['Location']);
	}

	public function test登録画面を表示する() {
        $this->testAction('/users/signup', ['method' => 'get']);
        $this->assertTextContains('タイムゾーン', $this->view);
	}

    public function testユーザー登録ができる() {
        $data['User'] = [
            'username' => 'zzz',
            'password' => 'zzzzzzzz',
            'password_retype' => 'zzzzzzzz',
            'screenname' => 'ずーずーずー',
            'email' => '',
            'timezone' => 'Asia/Tokyo',
            'remember' => true
        ];

        $this->testAction('/users/signup', ['data' => $data, 'method' => 'post']);
        $this->assertRegExp('/tasks$/', $this->headers['Location']);
    }

    public function testユーザー登録ができメール認証ページに飛ぶ() {
        $data['User'] = [
            'username' => 'zzz',
            'password' => 'zzzzzzzz',
            'password_retype' => 'zzzzzzzz',
            'screenname' => 'ずーずーずー',
            'email' => 'zzz@zzz.com',
            'timezone' => 'Asia/Tokyo',
            'remember' => true
        ];

        $this->testAction('/users/signup', ['data' => $data, 'method' => 'post']);
        $this->assertRegExp('/activate$/', $this->headers['Location']);
    }

/**
 * testLogin method
 *
 * @return void
 */
	public function testログイン画面を表示する() {
        $result = $this->testAction('/users/login', ['method' => 'get']);
        $this->assertTextContains('ログインID', $this->view);
	}

	public function testログインできる() {
        $data['User'] = [
            'username' => 'aaa',
            'password' => 'aaaa',   // Authコンポーネントがモックなので何でも同じ
            'remember' => ''
        ];
        $this->testAction('/users/login', ['method' => 'post', 'data' => $data]);
        $this->assertRegExp('/tasks$/', $this->headers['Location']);
	}

/**
 * testOauthTwitter method
 *
 * @return void
 */
	public function testOauthTwitter() {
		$this->markTestIncomplete('testOauthTwitter not implemented.');
	}

/**
 * testOauthTwitterCb method
 *
 * @return void
 */
	public function testOauthTwitterCb() {
		$this->markTestIncomplete('testOauthTwitterCb not implemented.');
	}

/**
 * testLogout method
 *
 * @return void
 */
	public function testLogout() {
		$this->markTestIncomplete('testLogout not implemented.');
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete('testEdit not implemented.');
	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {
		$this->markTestIncomplete('testDelete not implemented.');
	}

/**
 * testPassword method
 *
 * @return void
 */
	public function testPassword() {
		$this->markTestIncomplete('testPassword not implemented.');
	}

/**
 * testMailActivate method
 *
 * @return void
 */
	public function testMailActivate() {
		$this->markTestIncomplete('testMailActivate not implemented.');
	}

/**
 * testPasswordReset method
 *
 * @return void
 */
	public function testPasswordReset() {
		$this->markTestIncomplete('testPasswordReset not implemented.');
	}

/**
 * testPasswordResetForm method
 *
 * @return void
 */
	public function testPasswordResetForm() {
		$this->markTestIncomplete('testPasswordResetForm not implemented.');
	}

}
