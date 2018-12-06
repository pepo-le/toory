<?php
class Lockout extends AppModel {
    /**
     * ログイン失敗時の情報を記録する
     *
     * @param string $name ユーザー名
     * @param string $ip ユーザーip
     * @return mixed 更新結果
     */
    public function recordFailedLogin($name, $ip) {
        $id = $name . $ip;

        if ($this->Exists($id)) {
            // 更新処理
            $data = [
                'count' => "Lockout.count + 1",
                'modified' => "'" . date('Y-m-d H:i:s') . "'"
            ];

            return $this->updateAll($data, ['id' => $id]);
        } else {
            // 新規作成
            $data = [
                'id' => $id,
                'count' => 1,
            ];

            $this->create();
            return $this->save($data);
        }
    }

    /**
     * 期間内ログイン試行回数を返す
     *
     * @param string $name ユーザー名
     * @param string $ip ユーザーip
     * @return mixed 更新結果
     */
    public function failCount($name, $ip) {
        $id = $name . $ip;
        $time = date('Y-m-d H:i:s', strtotime('-30 minutes'));

        $options = array(
            'conditions' => array(
                'Lockout.id' => $id,
                'Lockout.modified >' => $time
            ),
            'fields' => array(
                'Lockout.count'
            ),
        );

        $data = $this->find('first', $options);
        if (!empty($data)) {
            return $data['Lockout']['count'];
        } else {
            return 0;
        }
    }
}

