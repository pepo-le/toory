<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Security' => array(
            'csrfExpires' => '+3 day',
            'csrfUseOnce' => false, // トークンを繰り返し使えるように
            'blackHoleCallback' => 'blackhole'
        ),
        'Flash',
        'Session',
        'Cookie' => array(
            'name' => 'toory',
            'time' => '30 days',
            'httpOnly' => true
        ),
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'tasks',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            )
        )
    );

    // Model
    public $uses = array('User', 'AutoLogin');

    // twitterのOAuth
    public $twitter_oauth_callback;

    // ユーザーデータが入る
    public $userdata;
    // 日付変更確認後の日付が入る
    public $date;

    public function beforeFilter() {
        if (Configure::read('debug')) {
            $this->components[] = 'DebugKit.Toolbar';
        }

        $twitter_oauth_callback = Router::url('/', true) . '/users/oauthtwittercb';

        // ユーザー関連ページを除いて非ログイン時でも閲覧可
        $this->Auth->allow();

        // Cookieログイン
        // ログインしておらずCookieがセットされているとき
        if (!$this->Auth->loggedIn() && $token = $this->Cookie->read('auth')) {
            // トークンの照合
            $result = $this->AutoLogin->userByLoginToken($token, $this->Cookie->read('user'));

            // ユーザーのトークンが有効な時
            if ($result) {
                $this->log(Router::url() . ' -Cookie-Login-, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                $this->Auth->login(array('id' => $result['user_id']));
                // 自動ログイン情報の更新
                $token = $this->AutoLogin->tokenRegistry($result['user_id'], $token);

                // ユーザー名をCookieに保存
                $this->Cookie->write('user', $this->Cookie->read('user'));
                // 新しいトークンCookieに保存
                $this->Cookie->write('auth', $token);
            }
        }

        // ログインしているときはユーザー情報をセット
        if ($this->Auth->loggedIn()) {
            $this->userdata = $this->User->fetchUser($this->Auth->user('id'));

            if ($this->userdata) {
                // タイムゾーンをセット
                date_default_timezone_set($this->userdata['User']['timezone']);

                // 日付更新確認（日付変更時刻を過ぎているか）
                if (time() > strtotime($this->userdata['User']['changetime'])) {
                    $this->date = date('Y-m-d');
                } else {
                    $this->date = date('Y-m-d', strtotime('-1 day'));
                }
            } else {
                $this->Auth->logout();
            }

            // ユーザー名をビューに渡す
            $this->set('user', $this->userdata);
        }
    }

    // ブラックホールのコールバック
    public function blackhole($type) {
        // ログイントークンとCookieの削除
        $this->Cookie->delete('user');
        $this->AutoLogin->deleteToken($this->Cookie->read('auth'));
        $this->Cookie->delete('auth');

        $this->Flash->error('セッションが切断されました');
        $this->Auth->logout();
        $this->redirect('/');
    }
}
