<?php
class AutoLogin extends AppModel {
    public $primaryKey = 'token';
    public $belongsTo = array('User');

    /**
     * ログイントークンを発行する
     *
     * 新しいトークンを発行し、古いトークンを破棄
     * DBへハッシュ化したトークンを登録
     *
     * @param int $id ユーザーID
     * @param string $old_token 古いトークン
     * @return string $token 新規発行したトークン
     */
    public function tokenRegistry($id, $old_token = false) {
        $token = $this->generateLoginToken();
        $expire = '+30 days';

        $data = array(
            'token' => Security::hash($token, 'sha256'),
            'user_id' => $id,
            'expire' => date('Y-m-d H:i:s', strtotime($expire))
        );

        // 新しいトークンを登録
        $this->create();
        $this->save($data);

        // 古いトークンを破棄
        if ($old_token) {
            $this->deleteAll(array('AutoLogin.token' => Security::hash($old_token, 'sha256')));
        }

        return $token;
    }

    /**
     * Cookieに保存したユーザー認証用トークンを渡すと該当ユーザーを返す
     *
     * @param string $token Cookieのトークン
     * @return int ユーザーID
     */
    public function userByLoginToken($token, $username) {
        $options = array(
            'conditions' => array(
                'AutoLogin.token' => Security::hash($token, 'sha256'),
                'User.username' => $username
            ),
            'fields' => array(
                'AutoLogin.user_id',
                'AutoLogin.expire'
            ),
            'recursive' => 0
        );

        $user = $this->find('first', $options);

        if ($user) {
            return $user['AutoLogin'];
        } else {
            return false;
        }
    }

    /**
     * トークンを削除
     *
     * @param $string トークン
     */
    public function deleteToken($token) {
        $this->deleteAll(array('AutoLogin.token' => Security::hash($token, 'sha256')));
    }

    /**
     * Cookieログイン用のトークンを生成
     *
     * @return string 30文字のランダムな英数字文字列
     */
    private function generateLoginToken() {
        while(true) {
            $token = self::makeRandStr(30);

            $options = array(
                'conditions' => array('AutoLogin.token' => Security::hash($token, 'sha256')),
                'recursive' => -1
            );

            if (!$this->find('count', $options)) {
                return $token;
            }
        }
    }
}
?>
