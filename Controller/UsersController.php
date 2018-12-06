<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {
    public $helper = array('flash');
    public $uses = array('User', 'AutoLogin', 'OneTimeToken', 'Lockout');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('delete', 'edit', 'password', 'mail_activate');
    }

    public function signup() {
        // 登録ユーザーでログインしているときはリダイレクト
        if ($this->Auth->loggedIn() && $this->userdata['User']['register']) {
            return $this->redirect('/tasks');
        }

        if ($this->request->is('post')) {
            // 再入力パスワードの照合
            if ($this->request->data['User']['password'] !== $this->request->data['User']['password_retype']) {
                $this->User->invalidate('password', 'パスワードが一致しません');
                $this->User->invalidate('password_retype');
                unset($this->request->data['User']['password']);
                unset($this->request->data['User']['password_retype']);

                // 他の入力値のバリデーション
                $this->User->set($this->request->data);
                $this->User->validates(array('fieldList' => array('username', 'password', 'screenname', 'emal', 'timezone')));

                return $this->render('signup');
            }

            // ユーザー登録
            $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
            $result = $this->User->addUser($this->request->data, $this->Auth->user('id'));

            // 登録に失敗した時
            if ($result === false) {
                $this->Flash->error('ユーザー登録ができませんでした');
                return $this->render('signup');
            }

            // 登録が成功したらログイン
            if($this->Auth->login()) {
                // 自動ログインのチェックが入っている時
                if ($this->request->data['User']['remember']) {
                    $token = $this->AutoLogin->tokenRegistry($this->Auth->user('id'));

                    // ユーザー名をCookieに保存
                    $this->Cookie->write('user', $this->request->data['User']['username']);
                    // 新しいトークンCookieに保存
                    $this->Cookie->write('auth', $token);
                }

                // メールアドレスが登録されているときはメールアドレスの確認をする
                if (!empty($result['User']['email'])) {
                    $this->Flash->success('ユーザー情報を登録しました');
                    return $this->redirect('/users/mail_activate');
                }

                return $this->redirect($this->Auth->redirect());
            }
        }

        $this->render('signup');
    }

    public function login() {
        // 登録ユーザーでログインしているときはリダイレクト
        if ($this->Auth->loggedIn() && $this->userdata['User']['register']) {
            return $this->redirect('/tasks');
        }

        if ($this->request->is('post')) {
            $username = $this->request->data['User']['username'];
            $clientIp = $this->request->ClientIp(false);

            // 同一のユーザー名またはIPでの直近のログイン試行回数を取得
            $failCount = max($this->Lockout->failCount('un-' . $username, ''), $this->Lockout->failCount('', 'ip-' . $clientIp));
            if ($failCount > 6) {
                $this->Flash->error(__('不正アクセス防止のため、一定の回数以上パスワード入力を間違えるとアカウントロックが発生する仕様となっています。のちほど再ログインをお試しください。'));
                return $this->render('login');
            } else if ($failCount === 0) {
                $this->Lockout->delete('un-' . $username);
                $this->Lockout->delete('ip-' . $clientIp);
            }

            // ログインが成功した時
            if($this->Auth->login()) {
                // ユーザーデータを取得
                $this->userdata = $this->User->fetchUser($this->Auth->user('id'));

                // 未登録ユーザーのときは登録ユーザーとしてログインできていないのでログイン画面を再表示
                if (!$this->userdata['User']['register']) {
                    // ユーザー名とipのログイン失敗履歴を記録
                    $this->Lockout->recordFailedLogin('un-' . $username, '');
                    $this->Lockout->recordFailedLogin('', 'ip-' . $clientIp);

                    unset($this->request->data['User']['password']);
                    $this->Flash->error(__('ユーザー名またはパスワードが違います'));
                    $this->render('login');
                    return;
                }

                // 自動ログインにチェックチェックが入っている時
                if ($this->request->data['User']['remember']) {
                    $token = $this->AutoLogin->tokenRegistry($this->Auth->user('id'));

                    // ユーザー名をCookieに保存
                    $this->Cookie->write('user', $this->request->data['User']['username']);
                    // 新しいトークンCookieに保存
                    $this->Cookie->write('auth', $token);
                }

                // ログイン日の更新
                $this->User->lastLogin($this->Auth->user('id'));

                $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                return $this->redirect($this->Auth->redirect());
            } else {
                // ユーザー名とipのログイン失敗履歴を記録
                $this->Lockout->recordFailedLogin('un-' . $username, '');
                $this->Lockout->recordFailedLogin('', 'ip-' . $clientIp);

                unset($this->request->data['User']['password']);
                $this->Flash->error(__('ユーザー名またはパスワードが違います'));
            }
        }

        $this->render('login');
    }

    /**
     * Twitter OAuth
     * リクエストトークンを取得
     */
    public function oauthTwitter() {
        $this->autoRender = false;
        $this->autoLayout = false;

        // 登録ユーザーでログインしているときはリダイレクト
        if ($this->Auth->loggedIn() && $this->userdata['User']['register']) {
            $this->redirect('/tasks');
        }

        $connection = new Abraham\TwitterOAuth\TwitterOAuth(
            Configure::read('twitter.consumer_key'),
            Configure::read('twitter.consumer_key_secret')
        );

        $request_token = $connection->oauth(
            'oauth/request_token',
            array(
                'oauth_callback' => $this->twitter_oauth_callback
            )
        );

        // リクエストトークンをセッションに保存
        $this->Session->write('oauth_token', $request_token['oauth_token']);
        $this->Session->write('oauth_token_secret', $request_token['oauth_token_secret']);

        // URLを生成してリダイレクト
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        $this->redirect($url);
    }

    /**
     * Twitter OAuth
     * アクセストークンを取得
     */
    public function oauthTwitterCb() {
        $this->autoRender = false;
        $this->autoLayout = false;

        // 登録ユーザーでログインしているときはリダイレクト
        if ($this->Auth->loggedIn() && $this->userdata['User']['register']) {
            $this->redirect('/tasks');
        }

        $request_token = array();
        $request_token['oauth_token'] = $this->Session->read('oauth_token');
        $request_token['oauth_token_secret'] = $this->Session->read('oauth_token_secret');

        // リクエストトークンが違う場合は中断
        if (empty($this->request->query['oauth_token']) || ($request_token['oauth_token'] !== $this->request->query['oauth_token'])) {
            $this->Session->write('oauth_token', 'oldtoken');

            $this->redirect('login');
        }

        // TwitterOAuthインスタンス生成
        $connection = new Abraham\TwitterOAuth\TwitterOAuth(
            Configure::read('twitter.consumer_key'),
            Configure::read('twitter.consumer_key_secret'),
            $request_token['oauth_token'],
            $request_token['oauth_token_secret']
        );

        // アクセストークンを取得
        $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $this->request->query['oauth_verifier']));

        // セッション
        // $this->Session->write('access_token', $access_token);
        $this->Session->delete('oauth_token');
        $this->Session->delete('oauth_token_secret');

        // 登録ユーザーの確認
        $user = $this->User->fetchOauthUser($access_token['oauth_token']);

        // ユーザー登録
        if (empty($user['User']['twtoken'])) {
            $connection = new Abraham\TwitterOAuth\TwitterOAuth(
                Configure::read('twitter.consumer_key'),
                Configure::read('twitter.consumer_key_secret'),
                $access_token['oauth_token'],
                $access_token['oauth_token_secret']
            );

            // twitterアカウント情報を取得
            $twitter_account = $connection->get('account/verify_credentials');

            // 登録するデータ
            $data = array(
                'username' => $twitter_account->screen_name,
                'password' => sha1(substr($access_token['oauth_token'], 11, 20)),
                'screenname' => $twitter_account->screen_name,
                'email_activation' => false,
                'twitter_register' => true,
                'twtoken' => $access_token['oauth_token'],
                'twtokensec' => $access_token['oauth_token_secret'],
                'register' => true
            );
            // 未登録ユーザーでログインしているとき
            if ($this->Auth->loggedIn()) {
                $data['id'] = $this->Auth->user('id');
            }

            // ユーザー登録
            $user = $this->User->addTwitterUser($data);
            $this->log('TwitterSignup, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');

            if ($user === false) {
                $this->Flash->error('ユーザー登録に失敗しました');
                $this->redirect('/users/signup');
            }
        }

        // ログイン処理
        if ($this->Auth->login(array('id' => $user['User']['id']))) {
            // 自動ログイン登録
            $token = $this->AutoLogin->tokenRegistry($user['User']['id']);

            // ユーザー名をCookieに保存
            $this->Cookie->write('user', $user['User']['username']);
            // 新しいトークンCookieに保存
            $this->Cookie->write('auth', $token);

            // ログイン日の更新
            $this->User->lastLogin($this->Auth->user('id'));

            $this->log('TwitterLogin, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
        } else {
            $this->Flash->error('ログインに失敗しました');
        }

        $this->redirect('/tasks');
    }

    public function logout() {
        // ログイントークンとCookieの削除
        $this->Cookie->delete('user');
        $this->AutoLogin->deleteToken($this->Cookie->read('auth'));
        $this->Cookie->delete('auth');

        $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
        $this->redirect($this->Auth->logout());
    }

    public function edit(){
        // 未登録ユーザーのときはリダイレクト
        if (!$this->userdata['User']['register']) {
            $this->redirect('/users/signup');
        }

        // メールアドレスの有無
        if (!empty($this->userdata['User']['email'])) {
            $this->set('email', true);
        } else {
            $this->set('email', false);
        }
        // メールアドレスの登録状況
        $this->set('email_activation', $this->userdata['User']['email_activation']);
        // Twitterユーザーのフラグ
        $this->set('twitter_register', $this->userdata['User']['twitter_register']);

        if ($this->request->is('post')) {
            // メールアドレスが変更されているときは認証解除フラグを立てる
            $change_address = false;
            if ($this->request->data['User']['email'] !== $this->userdata['User']['email']) {
                $change_address = true;
            }

            // 更新
            $result = $this->User->editUser($this->request->data, $change_address, $this->Auth->user('id'));

            if ($result === false) {
                $this->Flash->error('ユーザー情報の更新に失敗しました');
                $this->redirect('/users/edit');
            } else {
                $this->Flash->success('ユーザー情報を更新しました');

                // メールアドレスが登録され、
                // メールアドレスが追加または変更されたときは、メールアドレスの認証ページへリダイレクト
                if (!empty($result['User']['email']) && $this->userdata['User']['email'] !== $result['User']['email']) {
                    $this->redirect('/users/mail_activate');
                }

                $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                $this->redirect('/users/edit');
            }
        }

        // あらかじめフォームにユーザーの情報を入力しておく
        $this->request->data['User']['username'] = $this->userdata['User']['username'];
        $this->request->data['User']['screenname'] = $this->userdata['User']['screenname'];
        $this->request->data['User']['email'] = $this->userdata['User']['email'];
        $this->request->data['User']['timezone'] = $this->userdata['User']['timezone'];
        $this->request->data['User']['changetime'] = $this->userdata['User']['changetime'];
        $this->request->data['User']['vacation'] = $this->userdata['User']['vacation'];

        $this->render('edit');
    }

    public function delete() {
        // 未登録ユーザーのときはリダイレクト
        if (!$this->userdata['User']['register']) {
            $this->redirect('/users/signup');
        }

        if ($this->request->is('post')) {
            if ($this->request->data['User']['delete'] == true) {
                // Cookieの削除
                $this->Cookie->delete('user');
                $this->Cookie->delete('auth');

                $this->userdata = NULL;
                $result = $this->User->delete($this->Auth->user('id'), true);
                if ($result) {
                    $this->Flash->success('アカウントを削除しました');
                    $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                    $this->redirect('/users/signup');
                }
            }
        }

        $this->render('delete');
    }

    /**
     * パスワード変更
     */
    public function password() {
        // 未登録ユーザーのときはリダイレクト
        if (!$this->userdata['User']['register']) {
            $this->redirect('/users/signup');
        }

        if ($this->request->is('post')) {
            $error = false;

            // 新しいパスワードのバリデーション
            $this->User->set($this->request->data);
            if (!$this->User->validates(array('fieldList' => array('new_password')))) {
                $error = true;
            }

            // 現在のパスワードと照合
            $result = $this->User->verifyPassword($this->Auth->user('id'), $this->request->data['User']['password']);

            if (!$result) {
                $this->User->invalidate('password', 'パスワードが違います');
                unset($this->request->data['User']['password']);
                $error = true;
            }

            if ($this->request->data['User']['new_password'] !== $this->request->data['User']['new_password_retype']) {
                $this->User->invalidate('new_password', 'パスワードが一致しません');
                $this->User->invalidate('new_password_retype');
                unset($this->request->data['User']['new_password']);
                unset($this->request->data['User']['new_password_retype']);
                $error = true;
            }

            if ($error) {
                $this->render('password');
                return;
            }

            // パスワードを更新
            $result = $this->User->updatePassword($this->Auth->user('id'), $this->request->data['User']['new_password']);

            if ($result) {
                $this->Flash->success('パスワードを更新しました');
                $this->log(Router::url() . ', id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                $this->redirect('/users/edit');
            } else {
                $this->Flash->error('パスワードの更新に失敗しました');
                $this->redirect('/users/password');
            }
        }

        $this->render('password');
    }

    /**
     * メールアドレス認証
     */
    public function mail_activate() {
        // 未登録ユーザーのときはリダイレクト
        if (!$this->userdata['User']['register']) {
            $this->redirect('/users/signup');
        }
        // 認証済みのときはリダイレクト
        if ($this->userdata['User']['email_activation']) {
            $this->redirect('/users/edit');
        }

        // 既存のトークンを取得
        $token = $this->OneTimeToken->fetchTokenByUserId($this->Auth->user('id'), 'mail');

        // 既存のトークンがあるとき
        if ($token) {
            if (isset($this->request->query['token']) && isset($this->request->query['email'])) {
                // クエリのトークンと既存のトークンを照合
                if ($this->request->query['token'] === $token['OneTimeToken']['token']
                    && $this->request->query['email'] === substr($this->userdata['User']['email'], 0, strpos($this->userdata['User']['email'], '@'))
                ) {
                    // 一致しているときはトークンを削除
                    $this->OneTimeToken->deleteToken($this->Auth->user('id'), 'mail');

                    // メール送信から1時間以上経っているときは認証しない
                    $valid_period  = strtotime('+1 hour', strtotime($token['OneTimeToken']['created']));
                    if(time() > $valid_period) {
                        $this->Flash->error('認証URLの有効期限が切れています');
                        $this->redirect('/users/edit');
                    }

                    // 認証手続き
                    $result = $this->User->activateEmail($this->Auth->user('id'));
                    if ($result) {
                        $this->log('Activate, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                        $this->Flash->success('メールアドレスを確認しました');
                    } else {
                        $this->Flash->error('メールアドレスの確認ができませんでした');
                    }
                } else {
                    $this->redirect('/users/edit');
                }
                $this->redirect('/users/edit');
            }

            // 前回のメール送信が1時間以内のときはメールを送信しない
            $valid_period  = strtotime('+1 hour', strtotime($token['OneTimeToken']['created']));

            if(time() < $valid_period) {
                $this->set('sended', true);
                $this->render('mail_activate');
                return;
            }
        }

        $this->set('sended', false);
        // トークンを生成
        $result = $this->OneTimeToken->addToken($this->Auth->user('id'), 'mail');

        // メールを送信
        $email = new CakeEmail(Configure::read('email.type'));
        $email->from(array(Configure::read('email.address') => 'Toory'));
        $email->to($this->userdata['User']['email']);
        $email->subject('メールアドレスの確認');

        $body = $this->userdata['User']['username'] . " さん\n" .
                '以下のURLをにアクセスして、メールアドレスを確認してください。' . "\n\n" .
                substr(Router::url('/users/mail_activate/', true), 0, -1) .
                '?token=' . $result['OneTimeToken']['token'] .
                '&email=' . substr($this->userdata['User']['email'], 0, strpos($this->userdata['User']['email'], '@')) . "\n\n" .
                'URLは発行後1時間以内のみ有効です。' . "\n" .
                TITLE;

        $this->log('ActivateMailSend, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
        $mail_result = $email->send($body);

        if (!$mail_result) {
            $this->Flash->error('メールアドレス確認メールを送信できませんでした');
            $this->redirect('/users/edit');
        }

        $this->render('mail_activate');
    }

    public function password_reset() {
        if ($this->request->is('post')) {
            // ユーザーを検索
            $user = $this->User->fetchRegenerateUser($this->request->data['User']['username'], $this->request->data['User']['email']);

            if ($user) {
                // ユーザーが存在すればトークンを検索
                $token = $this->OneTimeToken->fetchTokenByUserId($user['User']['id'], 'password');

                if ($token) {
                    // 前回のメール送信が1時間以内のときはメールを送信しない
                    $valid_period  = strtotime('+1 hour', strtotime($token['OneTimeToken']['created']));
                    if(time() < $valid_period) {
                        $this->set('sended', true);
                        $this->render('password_reset_send');
                        return;
                    }
                }

                // トークンを生成
                $token = $this->OneTimeToken->addToken($user['User']['id'], 'password');

                if ($token) {
                    // メールを送信
                    $email = new CakeEmail(Configure::read('email.type'));
                    $email->from(array(Configure::read('email.address') => 'Toory'));
                    $email->to($user['User']['email']);
                    $email->subject('パスワードの再設定');
                    $body = '以下のURLをにアクセスして、パスワードを再設定してください。' . "\n\n" .
                            substr(Router::url('/users/password_reset_form/', true), 0, -1) .
                            '?token=' . $token['OneTimeToken']['token'] .
                            '&email=' . substr($user['User']['email'], 0, strpos($user['User']['email'], '@')) . "\n\n" .
                            'URLは発行後1時間以内のみ有効です。' . "\n" .
                            TITLE;
                    $this->log('ResetPasswordMail, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                    $mail_result = $email->send($body);

                    if (!$mail_result) {
                        $this->Flash->error('パスワード再設定メールを送信できませんでした');
                        $this->redirect('/users/login');
                    }

                    $this->set('sended', false);
                    $this->render('password_reset_send');
                    return;
                } else {
                    $this->Flash->error('パスワードの再設定ができませんでした');
                    $this->redirect('/users/login');
                }
            } else {
                $this->Flash->error('ユーザー名またはメールアドレスが違います');
            }
        }

        $this->render('password_reset');
    }

    public function password_reset_form() {
        // クエリが設定されていないとき
        if (!isset($this->request->query['token']) || !isset($this->request->query['email'])) {
            $this->redirect('/users/login');
        }

        // 既存のトークンを検索
        $token = $this->OneTimeToken->fetchTokenByToken($this->request->query['token'], 'password');

        if ($this->request->is('post') && $token) {
            // 再入力パスワードの照合
            if ($this->request->data['User']['password'] === $this->request->data['User']['password_retype']) {
                // パスワードを更新
                $result = $this->User->updatePassword($token['User']['id'], $this->request->data['User']['password']);

                if ($result) {
                    // 既存のトークンを削除
                    $this->OneTimeToken->deleteToken($token['User']['id'], 'password');

                    $this->Flash->success('パスワードを再設定しました。' . '<br />' . '新しいパスワードでログインしてください');
                    $this->log('ResetPassword, id:' . $this->Auth->user('id') . ', ip:' . $this->request->ClientIp(false), 'user_activity');
                    $this->redirect('/users/login');
                } else {
                    $this->Flash->error('パスワードの再設定に失敗しました');
                    $this->render('password_reset_form');
                }
            } else {
                // 入力パスワードが一致しないとき
                // モデルで設定済みのバリデーション
                $this->User->set($this->request->data);
                $this->User->validates(array('fieldList' => array('password')));

                $this->User->invalidate('password', 'パスワードが一致しません');
                $this->User->invalidate('password_retype');
                unset($this->request->data['User']['password']);
                unset($this->request->data['User']['password_retype']);

                // ログインIDを渡す
                $this->set('username', $token['User']['username']);

                $this->render('password_reset_form');
                return;
            }
        }

        // クエリのメールアドレスと登録ユーザーのメールアドレスを照合
        if ($token && $this->request->query['email'] === substr($token['User']['email'], 0, strpos($token['User']['email'], '@'))) {
            // メール送信から1時間以上経っているときは認証しない
            $valid_period  = strtotime('+1 hour', strtotime($token['OneTimeToken']['created']));
            if(time() > $valid_period) {
                // トークンの削除
                $this->OneTimeToken->deleteToken($token['User']['id'], 'password');
                $this->Flash->error('URLの有効期限が切れています');
                $this->redirect('/users/login');
            }

            // ログインIDを渡す
            $this->set('username', $token['User']['username']);

            $this->render('password_reset_form');
            return;
        }

        $this->redirect('/users/login');
    }
}
