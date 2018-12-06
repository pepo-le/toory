<?php
App::uses('User', 'Model');

/**
 * User Test Case
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

    public function testユーザー名は必須入力である() {
        $this->User->create(array('User' => array('username' => '')));
        $this->assertFalse($this->User->validates());
        $this->assertArrayHasKey('username', $this->User->validationErrors);
    }

    public function testパスワードは半角英数字記号である() {
        $this->User->create(array('User' => array('password' => 'abcabcabc')));
        $this->assertTrue($this->User->validates());
        $this->User->create(array('User' => array('password' => 'abc123abc')));
        $this->assertTrue($this->User->validates());
        $this->User->create(array('User' => array('password' => 'abcabc+bc')));
        $this->assertTrue($this->User->validates());
        $this->User->create(array('User' => array('password' => 'ａｂｃａｂｃａｂｃ')));
        $this->assertFalse($this->User->validates());
        $this->assertArrayHasKey('password', $this->User->validationErrors);
    }

    public function testユーザーを登録できる() {
        $data['User'] = array(
            'username' => 'uma',
            'password' => 'umaumauma',
            'screenname' => 'うまうま',
            'email' => 'uma@umauma.com',
            'email_activation' => false,
            'twitter_register' => false,
            'timezone' => 'Asia/Tokyo',
            'vacation' => false,
            'register' => true,
            'lastlogin' => date('Y-m-d')
        );

        $this->assertArrayHasKey('User', $this->User->addUser($data, 1));
    }

    public function testユーザー情報を更新できる() {
        $userdata = $this->User->fetchUser(1);

        $this->assertArrayHasKey('User', $this->User->editUser($userdata, true, 1));
    }

    public function testゲストユーザーを登録できる() {
        $this->assertArrayHasKey('User', $this->User->guestSignup());
    }

    public function testパスワードを照合できる() {
        $this->assertTrue($this->User->verifyPassword(1, 'aaaaaaaa'));
        $this->assertFalse($this->User->verifyPassword(1, 'bbbbbbbb'));
    }

    public function testパスワードを変更できる() {
        $this->assertArrayHasKey('User', $this->User->updatePassword(1, 'zzzzzzzz'));
        $this->assertTrue($this->User->verifyPassword(1, 'zzzzzzzz'));
    }

    public function testメールアドレスをアクティベートできる() {
        $user = $this->User->fetchUser(1);
        $this->assertFalse($user['User']['email_activation']);
        $this->assertArrayHasKey('User', $this->User->activateEmail(1));
        $user = $this->User->fetchUser(1);
        $this->assertTrue($user['User']['email_activation']);
    }

    public function testユーザーネームとメールアドレスからユーザーを取得できる() {
        $this->assertArrayHasKey('User', $this->User->fetchRegenerateUser('iii', 'ab123yz789@yahoo.co.jp'));
    }

    public function test登録していないIPはfalseを返す() {
        $this->assertFalse($this->User->isExistIP('1.2.3.4'));
    }

    public function test登録済みのIPはtrueを返す() {
        $this->assertTrue($this->User->isExistIP('11.22.33.44'));
    }

    /**
     * tearDown method
     *
     * @return void
     */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
