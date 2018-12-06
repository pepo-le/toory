<?php
App::uses('AppModel', 'Model');

class Reminder extends AppModel {
    public $belongsTo = array('Task', 'User');

    // リマインダー数の上限
    private $reminder_limit = 20;

    public $validate = array(
        'time' => array(
            'rule' => array('time'),
            'message' => '時刻が正しくありません'
        ),
        'datetime' => array(
            'rule' => array('datetime', 'ymd'),
            'message' => '日時が正しくありません'
        )
    );

    /**
     * リマインダーの追加
     *
     * リマインダーが登録上限内ならリマインダーを追加
     * 登録上限に達している時は指定配列を返す
     *
     * @param int $user_id ユーザーID
     * @param string $task_id タスクID
     * @param array $postdata POSTのデータ
     * @return mixed 配列またはfalse
     */
    public function addTimeReminder($user_id, $task_id ,$postdata) {
        // リマインダーの数をチェック
        if ($this->checkReminderLimit($user_id)) {
            return array('limit' => true);
        }

        $data = array(
            'user_id' => $user_id,
            'task_id' => $task_id,
            'type' => 0,
            'time' => $postdata['Task']['reminder_time'],
            'status' => false
        );

        $this->create();
        $result = $this->save($data);

        return $result;
    }

    public function addDatetimeReminder($user_id, $task_id ,$postdata) {
        // リマインダーの数をチェック
        if ($this->checkReminderLimit($user_id)) {
            return array('limit' => true);
        }

        $data = array(
            'user_id' => $user_id,
            'task_id' => $task_id,
            'type' => 1,
            'datetime' => $postdata['Task']['reminder_datetime'],
            'status' => false
        );

        $this->create();
        $result = $this->save($data);

        return $result;
    }

    public function editTimeReminder($user_id, $task_id, $postdata) {
        $this->deleteAll(array('task_id' => $task_id, 'type' => 0));

        // リマインダーの数をチェック
        if ($this->checkReminderLimit($user_id)) {
            return array('limit' => true);
        }

        if ($postdata['Task']['reminder_timecheck'] == true) {
            $time =  $postdata['Task']['reminder_time']['hour'] . ':' . $postdata['Task']['reminder_time']['min'];
            $data = array(
                'user_id' => $user_id,
                'task_id' => $task_id,
                'type' => 0,
                'time' => $time,
                'status' => false
            );
            $this->create();
            return $this->save($data);
        } else {
            return true;
        }
    }

    public function editDatetimeReminder($user_id, $task_id, $postdata) {
        $this->deleteAll(array('task_id' => $task_id, 'type' => 1));

        // リマインダーの数をチェック
        if ($this->checkReminderLimit($user_id)) {
            return array('limit' => true);
        }

        if ($postdata['Task']['reminder_datetimecheck'] == true) {
            $datetime = $postdata['Task']['reminder_datetime']['year'] . '-' .
                        $postdata['Task']['reminder_datetime']['month'] . '-' .
                        $postdata['Task']['reminder_datetime']['day'] . ' ' .
                        $postdata['Task']['reminder_datetime']['hour'] . ':' .
                        $postdata['Task']['reminder_datetime']['min'];

            $data = array(
                'user_id' => $user_id,
                'task_id' => $task_id,
                'type' => 1,
                'datetime' => $datetime,
                'status' => false
            );
            $this->create();
            return $this->save($data);
        } else {
            return true;
        }
    }

    /**
     * ユーザーのリマインダー登録数が上限に達しているかチェック
     *
     * @param int $user_id ユーザーID
     * @return boolean 結果（上限に達している時はtrue）
     */
    public function checkReminderLimit($user_id) {
        // リマインダーの数をチェック
        $options = array(
            'user_id' => $user_id
        );
        $count = $this->find('count', $options);

        // リマインダー上限に達している時
        if ($count >= $this->reminder_limit) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 有効期限内で、当日と昨日の曜日に有効なタスクを返す
     *
     * @param string $user_id ユーザーID
     * @param string $date 日付変更確認後の日付
     * @return array リマインダーデータの配列
     */
    public function fetchReminder($user_id, $date) {
        $day = date('w', strtotime($date));

        $options = array(
            'limit' => 20,
            'conditions' => array(
                'Reminder.status' => false,
                'User.id' => $user_id,
                'Task.begin <=' => $date,
                'Task.expire >=' => $date,
                'Task.status' => 0,
                'Task.day' . "${day}" => true
            ),
            'fields' => array(
                'Reminder.type',
                'Reminder.time',
                'Reminder.datetime',
                'Task.id',
                'Task.title',
                'Task.color',
            ),
            'order' => array(
                'Task.begin',
                'Task.title'
            ),
            'recursive' => 0
        );

        return $options;
    }

    /*
     * 有効期限内で、当日と昨日の曜日に有効なすべてのユーザーのタスクを返す
     *
     * @param string $date 日付の文字列
     * @return array リマインダーデータの配列
     */
    public function fetchAllReminder($date) {
        $yesterday_day = date('w', strtotime('-1 day', strtotime($date)));
        $day = date('w', strtotime($date));

        $options = array(
            'conditions' => array(
                'Reminder.status' => false,
                'Task.begin <=' => $date,
                'Task.expire >=' => $date,
                'Task.status' => 0,
                'OR' => array(
                    'Task.day' . "${yesterday_day}" => true,
                    'Task.day' . "${day}" => true
                ),
            ),
            'fields' => array(
                'Reminder.id',
                'Reminder.type',
                'Reminder.time',
                'Reminder.datetime',
                'User.screenname',
                'User.email',
                'User.timezone',
                'User.changetime',
                'User.vacation',
                'Task.title',
                'Task.body',
                'Task.task_type_id',
                'Task.day' . "${yesterday_day}",
                'Task.day' . "${day}",
                'Task.lastdone'
            ),
            'recursive' => 0
        );

        return $this->find('all', $options);
    }

    /**
     * リマインダーを削除する
     *
     * @param array $ids リマインダーIDの配列
     * @return bool 更新結果
     */
    public function stopReminder($ids) {
        $conditions = array(
            array('Reminder.id' => $ids)
        );

        return $this->deleteAll($conditions);
    }
}
