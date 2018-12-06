<?php
class OneTimeToken extends AppModel {
    public $belongsTo = array('User');

    /**
     * 新しいトークンを追加
     *
     * 既存のトークンは削除した上で新しいトークンを追加する
     *
     * @param int $user_id ユーザーID
     * @param string $type トークンの種類
     * @return mixed 処理結果
     */
    public function addToken($user_id, $type) {
        // 既存のリセットトークンを削除
        $this->deleteAll(array('user_id' => $user_id, 'type' => $type));

        $hash = Security::hash($user_id . time(), 'sha256');

        $data = array(
            'user_id' => $user_id,
            'token' => $hash,
            'type' => $type
        );

        $this->create();
        return $this->save($data);
    }

    /**
     * 既存のユーザーIDから既存のトークンを取得
     *
     * @param int $user_id ユーザーID
     * @param string $type トークンの種類
     * @return bool 取得結果
     */
    public function fetchTokenByUserId($user_id, $type) {
        $options = array(
            'conditions' => array(
                'OneTimeToken.user_id' => $user_id,
                'OneTimeToken.type' => $type
            ),
            'recursive' => 0
        );

        return $this->find('first', $options);
    }

    /**
     * トークンから既存のトークンを取得
     *
     * @param string $token トークン
     * @param string $type トークンの種類
     * @return bool 取得結果
     */
    public function fetchTokenByToken($token, $type) {
        $options = array(
            'conditions' => array(
                'OneTimeToken.token' => $token,
                'OneTimeToken.type' => $type
            ),
            'recursive' => 0
        );

        return $this->find('first', $options);
    }

    /**
     * トークンを削除
     *
     * @param int $user_id ユーザーID
     * @param string $type トークンの種類
     * @return bool 処理結果
     */
    public function deleteToken($user_id, $type) {
        return $this->deleteAll(array('user_id' => $user_id, 'type' => $type));
    }
}
