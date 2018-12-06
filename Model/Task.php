<?php
class Task extends AppModel {
    public $belongsTo = array('User', 'TaskType');
    public $hasMany = array(
        'Reminder' => array(
            'dependent' => true
        )
    );

    public $validate = array(
        'tasktype_id' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => '種類を選択してください'
            )
        ),
        'title' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'タスク名を入力してください'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 50),
                'message' => 'タスク名は50文字以内で入力してください'
            )
        ),
        'color' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'タスク色を選択してください'
            ),
            'rule2' => array(
                'rule' => '/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/i',
                'message' => 'タスク色の指定が不正です'
            )
        ),
        'body' => array(
            'rule' => array('maxLength', 80),
            'message' => 'タスク詳細は80文字以内で入力してください',
            'allowEmpty' => true
        ),
        'day' => array(
            'rule'     => array('multiple', array('min' => 1)),
            'message'  => '曜日を指定してください'
        ),
        'begin' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => '開始日を入力してください'
            ),
            'rule2' => array(
                'rule' => array('date', 'ymd'),
                'message' => '日付が正しくありません'
            )
        ),
        'expire' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => '終了日を入力してください'
            ),
            'rule2' => array(
                'rule' => array('date', 'ymd'),
                'message' => '日付が正しくありません'
            )
        )
    );


    /**
     * 40字のランダムな文字列を生成
     */
    public function generateId() {
        return self::makeRandStr(40);
    }

    /**
     * ユーザーのタスク総数を返す
     *
     * 登録上限内かチェックするために使用する
     *
     * @param int $id ユーザーID
     */
    public function countTask($user_id) {
        $options = [
            'conditions' => [
                'Task.user_id' => $user_id
            ],
            'recursive' => -1
        ];

        return $this->find('count', $options);
    }

    /**
     * 今日のルーチン総数を返す
     *
     * @param int $id ユーザーID
     * @param string $date 今日の日付
     * @return int 今日のルーチン総数
     */
    public function countRoutine($user_id, $date) {
        $options = array(
            'conditions' => array(
                'Task.user_id' => $user_id,
                'Task.task_type_id' => '1',
                'Task.begin <=' => $date,
                'Task.expire >=' => $date,
                'Task.day' . date('w', strtotime($date)) => true
            ),
            'recursive' => -1
        );

        return $this->find('count', $options);
    }

    /**
     * 今日のタスクを返す
     *
     * @param int $user_id ユーザーID
     * @param string $date 今日の日付
     * @return array タスクの配列
     */
    public function todayTask($user_id, $date) {
        $options = array(
            'conditions' => array(
                'Task.user_id' => $user_id,
                'Task.begin <=' => $date,
                'Task.expire >=' => $date,
                'Task.day' . date('w', strtotime($date)) => true
            ),
            'order' => 'Task.title ASC',
            'limit' => 51,
            'recursive' => -1
        );

        return $this->find('all', $options);
    }

    /**
     * 終了日前で完了していないTodoを返す
     *
     * @param int $id ユーザーID
     * @param date $date 日付変更確認後の日付
     * @return array paginateメソッドに渡す配列
     */
    public function fetchForwardTodo($user_id, $date) {
        $options = array(
            'limit' => 15,
            'conditions' => array(
                'Task.task_type_id' => 0,
                'Task.status' => 0,
                'Task.user_id' => $user_id,
                'Task.expire >=' => $date
            ),
            'order' => array(
                'Task.begin',
                'Task.title'
            ),
            'recursive' => -1
        );

        return $options;
    }

    /**
     * 終了したTodoを返す
     *
     * @param int $id ユーザーID
     * @param date $date 日付変更確認後の日付
     * @return array paginateメソッドに渡す配列
     */
    public function fetchDoneTodo($user_id, $date) {
        $options = array(
            'limit' => 15,
            'conditions' => array(
                'Task.user_id' => $user_id,
                'Task.task_type_id' => 0,
                'OR' => array(
                    'Task.status' => 1,
                    'Task.expire <' => $date
                )
            ),
            'order' => array(
                'Task.begin',
                'Task.title'
            ),
            'recursive' => -1
        );

        return $options;
    }

    /**
     * ルーチンの一覧を取得するPaginate用配列を返す
     *
     * 引数のSQLにより現在有効なルーチン、
     * 将来のルーチン、過去のルーチンを選択する
     *
     * @param int $id ユーザーID
     * @param string $date_sql 日付条件を記述したSQL
     * @return array ルーチンデータの配列
     */
    public function fetchRoutine($user_id, $date_sql) {
        $options = array(
            'limit' => 15,
            'conditions' => array(
                'Task.task_type_id' => 1,
                'Task.user_id' => $user_id,
                $date_sql
            ),
            'order' => array(
                'Task.begin',
                'Task.title'
            ),
            'recursive' => -1
        );

        return $options;
    }

    /**
     * 新規タスクを作成
     *
     * @param int $user_id ユーザーID
     * @param array $postdata タスク登録フォームのPOSTデータ
     * @return array 登録したタスクの配列
     */
    public function createTask($user_id, $postdata) {
        // 有効期限のチェックが入っていない時
        if ($postdata['Task']['expirecheck'] == false || empty($postdata['Task']['expire'])) {
            $postdata['Task']['expire'] = '9999-12-31';
        }

        $data = array(
            'user_id' => $user_id,
            'task_type_id' => $postdata['Task']['tasktype_id'],
            'title' => $postdata['Task']['title'],
            'color' => $postdata['Task']['color'],
            'body' => $postdata['Task']['body'],
            'day0' => false,
            'day1' => false,
            'day2' => false,
            'day3' => false,
            'day4' => false,
            'day5' => false,
            'day6' => false,
            'begin' => $postdata['Task']['begin'],
            'expire' => $postdata['Task']['expire']
        );

        // 曜日が選択されている時
        if ($postdata['Task']['day']) {
            // セレクトボックスの曜日配列データを処理
            foreach($postdata['Task']['day'] as $item) {
                switch ($item) {
                    case 0:
                        $data['day0'] = true;
                        break;
                    case 1:
                        $data['day1'] = true;
                        break;
                    case 2:
                        $data['day2'] = true;
                        break;
                    case 3:
                        $data['day3'] = true;
                        break;
                    case 4:
                        $data['day4'] = true;
                        break;
                    case 5:
                        $data['day5'] = true;
                        break;
                    case 6:
                        $data['day6'] = true;
                        break;
                }
            }
        } else {
            return false;
        }

        // IDを生成してデータに追加
        while (true) {
            $id = $this->generateId();
            if (!$this->exists($id)) {
                $data['id'] = $id;
                break;
            }
        }

        $this->create();
        return $this->save($data);
    }

    /**
     * タスクを完了する
     *
     * @param int $id タスクID
     * @return array タスクの配列
     */
    public function done($id, $type, $date) {
        $data = array(
            'secondlastdone' => "Task.lastdone",
            'lastdone' => '"' . $date . '"'
        );

        if ($type == 1) {
            $data['status'] = 1;
        }

        // データを更新
        return $this->updateAll($data, array('Task.id' => $id));
    }

    /**
     * タスクの詳細を取得する
     *
     * @param string $id タスクID
     * @param int $user_id ユーザーID
     * @return array タスクの配列
     */
    public function fetchTask($id, $user_id) {
        $options = array(
            'conditions' => array(
                'Task.id' => $id,
                'Task.user_id' => $user_id
            ),
            'recursive' => 1
        );

        return $this->find('first', $options);
    }

    /**
     * 既存のタスクを編集する
     *
     * @param int $id タスクID
     * @param array $postdata タスク編集フォームのPOSTデータ
     * @return mixed 更新結果
     */
    public function editTask($id, $postdata) {
        // 有効期限のチェックが入っていない時
        if ($postdata['Task']['expirecheck'] == false || empty($postdata['Task']['expire'])) {
            $postdata['Task']['expire'] = '9999-12-31';
        }

        $data = array(
            'id' => $id,
            'title' => $postdata['Task']['title'],
            'color' => $postdata['Task']['color'],
            'body' => $postdata['Task']['body'],
            'status' => $postdata['Task']['status'],
            'day0' => false,
            'day1' => false,
            'day2' => false,
            'day3' => false,
            'day4' => false,
            'day5' => false,
            'day6' => false,
            'begin' => $postdata['Task']['begin'],
            'expire' => $postdata['Task']['expire'],
            'lastdone' => $postdata['Task']['lastdone']
        );

        // 有効期限のチェックが入っていない時
        if ($postdata['Task']['expirecheck'] == false) {
            $data['expire'] = '9999-12-31';
        }

        // 曜日が選択されている時
        if ($postdata['Task']['day']) {
            foreach($postdata['Task']['day'] as $day) {
                switch ($day) {
                    case 0:
                        $data['day0'] = true;
                        break;
                    case 1:
                        $data['day1'] = true;
                        break;
                    case 2:
                        $data['day2'] = true;
                        break;
                    case 3:
                        $data['day3'] = true;
                        break;
                    case 4:
                        $data['day4'] = true;
                        break;
                    case 5:
                        $data['day5'] = true;
                        break;
                    case 6:
                        $data['day6'] = true;
                        break;
                }
            }
        } else {
            return false;
        }

        return $this->save($data);
    }

    /**
     * タスクの完了をキャンセルする
     *
     * @param int $id タスクID
     * @return bool 更新結果
     */
    public function cancel($id) {
        $data = array(
            'status' => 0,
            'lastdone' => "Task.secondlastdone"
        );

        // データを更新
        return $this->updateAll($data, array('Task.id' => $id));
    }
}
