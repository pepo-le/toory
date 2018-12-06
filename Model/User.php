<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $displayField = 'username';

    public $hasMany = array(
        'Task' => array(
            'dependent' => true
        ),
        'History' => array(
            'dependent' => true
        ),
        'AutoLogin' => array(
            'dependent' => true
        ),
        'Reminder' => array(
            'dependent' => true
        )
    );

    public $validate = array(
        'username' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'require' => true,
                'allowEmpty' => false,
                'message' => 'ログインIDを入力してください'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 30),
                'message' => 'ログインIDは30文字以内で入力してください'
            ),
            'rule3' => array(
                'rule' => array('minLength', 5),
                'message' => 'ログインIDは5文字以上で入力してください'
            ),
            'rule4' => array(
                'rule' => 'isUnique',
                'message' => 'そのログインIDは既に使われています'
            ),
            'rule5' => array(
                'rule' => 'alphaNumeric',
                'message' => 'ログインIDは半角英数字で入力してください'
            )
        ),
        'screenname' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'require' => true,
                'allowEmpty' => false,
                'message' => 'ユーザー名を入力してください'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 30),
                'message' => 'ユーザー名は30文字以内で入力してください'
            )
        ),
        'password' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'require' => true,
                'allowEmpty' => false,
                'message' => 'パスワードを入力してください'
            ),
            'rule2' => array(
                'rule' => array('between', 8, 100),
                'message' => 'パスワードは8文字以上100文字以内で入力してください'
            ),
            'rule3' => array(
                'rule' => 'alphanumericsymbols',
                'message' => 'パスワードは半角英数字記号で入力してください'
            )
        ),
        'email' => array(
            'rule1' => array(
                'rule' => 'email',
                'allowEmpty' => true,
                'message' => 'メールアドレスの形式ではありません'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 255),
                'message' => 'メールアドレスは255文字以内で入力してください'
            ),
        ),
        'timezone' => array(
            'rule1' => array(
                'rule' => array('inList', self::TIMEZONE_LIST),
                'require' => true,
                'allowEmpty' => false,
                'message' => '正しいタイムゾーンではありません'
            )
        ),
        'changetime' => array(
            'rule1' => array(
                'rule' => 'time',
                'require' => true,
                'allowEmpty' => false,
                'message' => '時刻の形式が正しくありません'
            )
        ),
        'vacation' => array(
            'rule1' => array(
                'rule' => array('boolean'),
                'message' => '休暇モードの送信データの形式が正しくありません'
            )
        ),
        'new_password' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'require' => true,
                'allowEmpty' => false,
                'message' => 'パスワードを入力してください'
            ),
            'rule2' => array(
                'rule' => array('between', 8, 100),
                'message' => 'パスワードは8文字以上100文字以内で入力してください'
            ),
            'rule3' => array(
                'rule' => 'alphanumericsymbols',
                'message' => 'パスワードは半角英数字記号で入力してください'
            )
        )
    );

    /**
     * 英数字と記号のバリデーション
     *
     * @param array $check 入力文字が入った配列
     * @return int 正規表現の判定結果
     */
    public function alphanumericsymbols($check) {
        $value = array_values($check);
        $value = $value[0];
        return preg_match('/^[a-zA-Z0-9\s\x21-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]+$/', $value);
    }

    const TIMEZONE_LIST = array('Pacific/Midway', 'America/Adak', 'Etc/GMT+10', 'Pacific/Marquesas', 'Pacific/Gambier', 'America/Anchorage', 'America/Ensenada', 'Etc/GMT+8', 'America/Los_Angeles', 'America/Denver', 'America/Chihuahua', 'America/Dawson_Creek', 'America/Belize', 'America/Cancun', 'Chile/EasterIsland', 'America/Chicago', 'America/New_York', 'America/Havana', 'America/Bogota', 'America/Caracas', 'America/Santiago', 'America/La_Paz', 'Atlantic/Stanley', 'America/Campo_Grande', 'America/Goose_Bay', 'America/Glace_Bay', 'America/St_Johns', 'America/Araguaina', 'America/Montevideo', 'America/Miquelon', 'America/Godthab', 'America/Argentina/Buenos_Aires', 'America/Sao_Paulo', 'America/Noronha', 'Atlantic/Cape_Verde', 'Atlantic/Azores', 'Europe/Belfast', 'Europe/Dublin', 'Europe/Lisbon', 'Europe/London', 'Africa/Abidjan', 'Europe/Amsterdam', 'Europe/Belgrade', 'Europe/Brussels', 'Africa/Algiers', 'Africa/Windhoek', 'Asia/Beirut', 'Africa/Cairo', 'Asia/Gaza', 'Africa/Blantyre', 'Asia/Jerusalem', 'Europe/Minsk', 'Asia/Damascus', 'Europe/Moscow', 'Africa/Addis_Ababa', 'Asia/Tehran', 'Asia/Dubai', 'Asia/Yerevan', 'Asia/Kabul', 'Asia/Yekaterinburg', 'Asia/Tashkent', 'Asia/Kolkata', 'Asia/Katmandu', 'Asia/Dhaka', 'Asia/Novosibirsk', 'Asia/Rangoon', 'Asia/Bangkok', 'Asia/Krasnoyarsk', 'Asia/Hong_Kong', 'Asia/Irkutsk', 'Australia/Perth', 'Australia/Eucla', 'Asia/Tokyo', 'Asia/Seoul', 'Asia/Yakutsk', 'Australia/Adelaide', 'Australia/Darwin', 'Australia/Brisbane', 'Australia/Hobart', 'Asia/Vladivostok', 'Australia/Lord_Howe', 'Etc/GMT-11', 'Asia/Magadan', 'Pacific/Norfolk', 'Asia/Anadyr', 'Pacific/Auckland', 'Etc/GMT-12', 'Pacific/Chatham', 'Pacific/Tongatapu', 'Pacific/Kiritimati');

    /**
     * 新規ユーザーを登録する
     *
     * @param array $postdata POSTデータ
     * @param int $user_id ユーザーID
     * @return bool 登録結果
     */
    public function addUser($postdata, $user_id = false) {
        $data = array(
            'username' => $postdata['User']['username'],
            'password' => $postdata['User']['password'],
            'screenname' => $postdata['User']['screenname'],
            'email' => $postdata['User']['email'],
            'email_activation' => false,
            'twitter_register' => false,
            'timezone' => $postdata['User']['timezone'],
            'vacation' => false,
            'ip' => NULL,
            'register' => true,
            'lastlogin' => date('Y-m-d')
        );

        if (isset($user_id)) {
            $data['id'] = $user_id;
        }

        $this->create();
        return $this->save($data);
    }

    /**
     * Twitterユーザーを登録する
     *
     * @param array $data 登録データ
     * @return bool 登録結果
     */
    public function addTwitterUser($data) {
        $this->create();
        return $this->save($data);
    }

    /**
     * ユーザー情報を更新する
     *
     * @param array $postdata POSTのデータ
     * @param bool $change_address メールアドレスの変更フラグ
     * @param int $user_id ユーザーID
     * @return array ユーザーデータの配列
     */
    public function editUser($postdata, $change_address, $user_id) {
        // 更新データ
        $data = array(
            'id' => $user_id,
            'screenname' => $postdata['User']['screenname'],
            'email' => $postdata['User']['email'],
            'timezone' => $postdata['User']['timezone'],
            'changetime' => $postdata['User']['changetime'],
        );

        // Twitterユーザーではないとき
        if (isset($postdata['User']['username'])) {
            $data['username'] = $postdata['User']['username'];
        }

        // メールアドレス認証ができていないときなど
        if (empty($postdata['User']['vacation'])) {
            $data['vacation'] = false;
        } else {
            $data['vacation'] = $postdata['User']['vacation'];
        }

        if ($change_address) {
            $data['email_activation'] = false;
        }

        // 更新
        return $this->save($data);
    }

    /**
     * IDからユーザー情報を取得する
     *
     * @param int $id ユーザーID
     * @return array ユーザー情報
     */
    public function fetchUser($id) {
        $options = array(
            'conditions' => array(
                'User.id' => $id
            ),
            'fields' => array(
                'User.username',
                'User.screenname',
                'User.email',
                'User.email_activation',
                'User.twitter_register',
                'User.timezone',
                'User.changetime',
                'User.register',
                'User.vacation'
            ),
            'recursive' => -1
        );

        return $this->find('first', $options);
    }

    /**
     * Twitter OAuthトークンからユーザー情報を取得する
     *
     * @param string $oauth_token OAuthトークン
     * @return array ユーザー情報
     */
    public function fetchOauthUser($oauth_token) {
        $options = array(
            'conditions' => array(
                'User.twtoken' => $oauth_token
            ),
            'recursive' => -1
        );
        return $this->find('first', $options);
    }

    /**
     * ゲストユーザーを登録する
     *
     * @param string $ip アクセス元のIP
     * @return int ユーザーID
     */
    public function guestSignup($ip = NULL) {
        // ランダムなユーザー名を生成
        while(true) {
            $username = self::makeRandStr(20);

            $options = array(
                'conditions' => array('User.username' => $username),
                'recursive' => -1
            );

            if (!$this->find('count', $options)) {
                break;
            }
        }

        $userdata = array(
            'username' => $username,
            'password' => self::makeRandStr(20),
            'screenname' => 'ゲスト',
            'email' => '',
            'email_activation' => false,
            'twitter_register' => false,
            'timezone' => 'Asia/Tokyo',
            'vacation' => false,
            'ip' => $ip,
            'changetime' => '00:00:00',
            'register' => '0'
        );

        // ユーザーデータを登録
        $this->create();
        $user = $this->save($userdata);

        // パスワードなどを削除したユーザーデータを返す
        if ($user === false) {
            return false;
        } else {
            unset($user['User']['password']);
            unset($user['User']['created']);
            unset($user['User']['modified']);
            return $user;
        }
    }

    /**
     * 新しいIPアドレスかチェック
     *
     * @param string $ip アクセス元のIP
     * @return boolean
     */
    public function isExistIP($ip) {
        $options = array(
            'conditions' => array(
                'User.ip' => $ip
            ),
            'fields' => array(
                'User.id',
            ),
            'recursive' => -1
        );

        return (bool)$this->find('first', $options);
    }

    /**
     * ログイン日を更新する
     *
     * @param int $id ユーザーID
     */
    public function lastLogin($id) {
        $data = array(
            'id' => $id,
            'lastlogin' => date('Y-m-d')
        );

        $this->save($data);
    }

    /**
     * パスワードの照合をする
     *
     * @param int $user_id ユーザーID
     * @param string $password Postされたパスワード
     * @return bool 照合結果
     */
    public function verifyPassword($user_id, $password) {
        $options = array(
            'conditions' => array(
                'User.id' => $user_id
            ),
            'fields' => array(
                'User.password'
            ),
            'recursive' => -1
        );

        $user = $this->find('first', $options);

        // 登録パスワードのソルト
        $salt = substr($user['User']['password'], 0, 29);

        // BlowfishのハッシュとDBのパスが一致するか
        return crypt($password, $salt) === $user['User']['password'];
    }

    /**
     * パスワードを更新する
     *
     * @param int $user_id ユーザーID
     * @param string $password 新パスワード
     * @return bool 更新結果
     */
    public function updatePassword($user_id, $password) {
        $this->id = $user_id;
        return $this->saveField('password', $password, true);
    }

    /**
     * メールアドレスをアクティベートする
     *
     * @param int $id ユーザーID
     * @return bool 結果
     */
    public function activateEmail($user_id) {
        $this->id = $user_id;
        return $this->saveField('email_activation', true);
    }

    /**
     * パスワード再発行用のユーザー情報を返す
     *
     * @param string $username ログインID
     * @param string $email メールアドレス
     * @return mixed 取得結果
     */
    public function fetchRegenerateUser($username, $email) {
        $options = array(
            'conditions' => array(
                'User.username' => $username,
                'User.email' => $email
            ),
            'fields' => array(
                'User.id',
                'User.email'
            ),
            'recursive' => -1
        );

        return $this->find('first', $options);
    }
}
