<?php
App::uses('AppController', 'Controller');

class TasksController extends AppController {
    public $uses = array('Task', 'User', 'TaskType', 'History', 'Reminder');

    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        // フォームの改ざんチェックを無効（ajax対応のため）
        $this->Security->validatePost = false;

        // ログインしていないときはゲストユーザーを登録する
        if (!$this->Auth->loggedIn()) {
            // IPが同じゲストユーザーがいたら削除
            if ($this->User->isExistIP($this->request->clientIP())) {
                $this->User->deleteAll(['User.ip' => $this->request->clientIP()], true);
            }

            // ゲストユーザーを登録
            $this->userdata = $this->User->guestSignup($this->request->clientIP());

            if ($this->userdata) {
                if ($this->Auth->login(array('id' => $this->userdata['User']['id']))) {
                    // 自動ログイン登録
                    $token = $this->AutoLogin->tokenRegistry($this->userdata['User']['id']);

                    // ユーザー名をCookieに保存
                    $this->Cookie->write('user', $this->userdata['User']['username']);
                    // 新しいトークンCookieに保存
                    $this->Cookie->write('auth', $token);

                    // ログイン日の更新
                    $this->User->lastLogin($this->Auth->user('id'));

                    // デフォルトの日付データを追加
                    $this->date = date('Y-m-d');

                    // ユーザー名をビューに渡す
                    $this->set('user', $this->userdata);
                }
            } else {
                // ログインできなかった時
                $this->redirect('/users/signup');
            }

            // タイムゾーンをセット
            date_default_timezone_set($this->userdata['User']['timezone']);
        }
    }

    /**
     * タスクのトップ
     *
     * 今日のタスク一覧を表示する
     */
    public function index() {
        $this->response->disableCache();

        // ログインユーザーの今日のタスクをビューに渡す
        $tasks_data = $this->Task->todayTask($this->Auth->user('id'), $this->date);

        if (count($tasks_data) > 50) {
            $this->Flash->error('今日のタスクが表示上限を超えています（上限50件）');
            array_pop($tasks_data);
        }

        $this->set('tasks_data', $tasks_data);
        $this->set('date', $this->date);

        $this->render('index');
    }

    /**
     * タスクの詳細を表示
     *
     * パラメータからタスクIDを取得し表示する
     */
    public function detail() {
        // パラメータを取得
        $id = '';
        if (isset($this->request->params['pass'][0])){
            $id = $this->request->params['pass'][0];
        }

        $task = $this->Task->fetchTask($id, $this->Auth->user('id'));

        if (!$task) {
            throw new NotFoundException();
        }

        // 既存データの曜日情報を配列に展開
        $day = array();
        for ($i = 0; $i < 7; $i++) {
            if ($task['Task']["day${i}"] === true) {
                $day[] = $i;
            }
        }
        $this->set('day', $day);

        $this->set('task', $task);
        $this->render('detail');
    }

    /**
     * Todoの一覧を表示
     *
     * パラメータにより表示するTodoを変える
     * パラメータ無し：現在有効なルーチン
     * /forward：これからのTodo
     * /done：終了したTodo
     */
    public function todo() {
        // パラメータを取得
        $type = '';
        if (isset($this->request->params['pass'][0])){
            $type = $this->request->params['pass'][0];
        }

        // まとめてタスクを削除
        if ($this->request->is('post')) {
            $result = $this->delete_collectively($this->request->data);

            if ($result) {
                $this->Flash->success('タスクを削除しました');
            } else {
                $this->Flash->error('タスクを削除できませんでした');
            }

            $this->redirect('/tasks/todo/' . $type);
        }

        // パラメータによって取得データを変える
        $date = $this->date;
        switch($type) {
            case '':
                $this->paginate = $this->Task->fetchForwardTodo($this->Auth->user('id'), $this->date);
                break;
            case 'done':
                $this->paginate = $this->Task->fetchDoneTodo($this->Auth->user('id'), $this->date);
                break;
            default:
                throw new NotFoundException();
                break;
        }

        // ルーチンをビューに渡す
        $tasks_data = $this->paginate('Task');
        $this->set('tasks_data', $tasks_data);

        $this->set('view_type', $type);

        $this->render('todo');
    }

    /**
     * ルーチンの一覧を表示
     *
     * パラメータにより表示するルーチンを変える
     * /active：現在有効なルーチン
     * /forward：将来のルーチン
     * /done：終了したルーチン
     */
    public function routine() {
        // パラメータを取得
        $type = '';
        if (isset($this->request->params['pass'][0])){
            $type = $this->request->params['pass'][0];
        }

        // まとめてタスクを削除
        if ($this->request->is('post')) {
            $result = $this->delete_collectively($this->request->data);

            if ($result) {
                $this->Flash->success('タスクを削除しました');
            } else {
                $this->Flash->error('タスクを削除できませんでした');
            }

            $this->redirect('/tasks/routine/' . $type);
        }

        // パラメータによって日付部分のSQLを変える
        $date = $this->date;
        switch($type) {
            case '':
                $date_sql = 'Task.expire >= "' . $this->date . '" AND Task.begin <= "' . $this->date . '"';
                break;
            case 'forward':
                $date_sql = 'Task.begin > "' . $this->date . '"';
                break;
            case 'done':
                $date_sql = 'Task.expire < "' . $this->date . '"';
                break;
            default:
                throw new NotFoundException();
                break;
        }

        // ルーチンをビューに渡す
        $this->paginate = $this->Task->fetchRoutine($this->Auth->user('id'), $date_sql);
        $tasks_data = $this->paginate('Task');
        $this->set('tasks_data', $tasks_data);

        $this->set('view_type', $type);

        $this->render('routine');
    }

    /**
     * タスクを完了する
     */
    public function done() {
        $this->autoRender = false;

        // postの場合
        if ($this->request->is('post')) {
            // 別のウィンドウ/デバイスで更新されているかチェック
            $task = $this->Task->fetchTask($this->request->data['Task']['id'], $this->Auth->user('id'));
            if ($task['Task']['lastdone'] === $this->date) {
                // 今日完了していたら
                $result = true;
            } else {
                // データを更新
                $result = $this->Task->done($this->request->data['Task']['id'], $this->request->data['Task']['task_type_id'], $this->date);
            }

            // historyの更新
            if ($result && $task['Task']['lastdone'] !== $this->date) {
                $history_result = $this->History->addHistory(
                    $this->Auth->user('id'),
                    $this->request->data['Task']['task_type_id'],
                    $this->date
                );
            }

            // ajaxの使用チェック
            if ($this->request->is('ajax')) {
                if ($result === false) {
                    $res = [
                        'result' => [
                            'code' => 500,
                            'message' => 'Internet Information Server'
                        ],
                    ];
                } else {
                    $res = [
                        'result' => [
                            'code' => 200
                        ]
                    ];
                }
                return $this->response->body(json_encode($res));
            } else {
                if ($result === false) {
                    $this->Flash->error('更新に失敗しました');
                    $this->redirect('/tasks');
                } else {
                    $this->Flash->success('更新しました');
                    $this->redirect('/tasks');
                }
            }
        } else {
            // postでない場合リダイレクト
            $this->redirect('/tasks');
        }
    }

    public function cancel() {
        $this->autoRender = false;

        // postの場合
        if ($this->request->is('post')) {
            // 別のウィンドウ/デバイスで更新されているかチェック
            $task = $this->Task->fetchTask($this->request->data['Task']['id'], $this->Auth->user('id'));
            if ($task['Task']['lastdone'] !== $this->date) {
                // 今日キャンセル済みなら
                $result = true;
            } else {
                // データを更新
                $result = $this->Task->cancel($this->request->data['Task']['id']);
            }

            // historyの更新
            if ($result && $task['Task']['lastdone'] === $this->date) {
                $this->History->rollbackHistory(
                    $this->Auth->user('id'),
                    $this->date,
                    $this->request->data['Task']['task_type_id']
                );
            }

            // ajaxの使用チェック
            if ($this->request->is('ajax')) {
                if ($result === false) {
                    $res = [
                        'result' => [
                            'code' => 500,
                            'message' => 'Internet Information Server'
                        ],
                    ];
                } else {
                    $res = [
                        'result' => [
                            'code' => 200
                        ]
                    ];
                }
                return $this->response->body(json_encode($res));
            } else {
                if ($result === false) {
                    $this->Flash->error('更新に失敗しました');
                    $this->redirect('/tasks');
                } else {
                    $this->Flash->success('更新しました');
                    $this->redirect('/tasks');
                }
            }
        } else {
            // postでない場合リダイレクト
            $this->redirect('/tasks');
        }
    }

    public function create() {
        // ユーザー情報がないときはリダイレクト
        if (!$this->Auth->loggedIn()) {
            $this->redirect('/users/signup');
        }

        // ビューでタスクタイプを参照できるようにデータを渡す
        $this->set('tasktypes', $this->TaskType->find('list'));
        // 日付変更をチェックした日付を渡す
        $this->set('date', $this->date);
        // メールアドレスフラグ
        $this->set('email_activation', $this->userdata['User']['email_activation']);
        // 登録ユーザーフラグ
        $this->set('register', $this->userdata['User']['register']);

        // POSTの時
        if ($this->request->is('post')) {
            // タスク登録数をチェック
            if ($this->Task->countTask($this->Auth->user('id')) > 1000) {
                $this->Flash->error('タスクの登録数が上限を超えています（上限1,000件）');
                $this->redirect('/tasks');
            }

            // dayを先にバリデーション
            $this->Task->set($this->request->data);
            // dayが妥当ではないときは中断
            if (!$this->Task->validates(array('fieldList' => array('day')))) {
                return $this->render('create');
            }

            // typeのバリデーション
            if ($this->request->data['Task']['tasktype_id'] != 0 && $this->request->data['Task']['tasktype_id'] != 1) {
                $this->Task->invalidate('tasktype_id', '種類が不正です');
                return $this->render('create');
            }

            // 開始日が有効期限より遅いときは中断
            if ($this->request->data['Task']['expirecheck'] && isset($this->request->data['Task']['expire'])
                && $this->request->data['Task']['begin'] > $this->request->data['Task']['expire']
            ) {
                $this->Task->invalidate('expire', '有効期限は開始日以降の日付を指定してください');
                unset($this->request->data['Task']['expirecheck']);
                unset($this->request->data['Task']['expire']);
                return $this->render('create');
            }

            // リマインダーのバリデーション
            // 無効なPOSTデータを除去するためにオブジェクトを渡す
            if ($this->userdata['User']['email_activation']) {
                $error = $this->validateReminder($this->request);
                if ($error) {
                    return $this->render('create');
                }
            }

            // タスクを作成
            $result = $this->Task->createTask($this->Auth->user('id'), $this->request->data);

            // リマインダーを作成
            if ($this->userdata['User']['email_activation']) {
                if (!empty($this->request->data['Task']['reminder_timecheck'])) {
                    $reminder_time_result = $this->Reminder->addTimeReminder($this->Auth->user('id'), $result['Task']['id'], $this->request->data);
                    if (isset($reminder_time_result['limit'])) {
                        $this->Flash->error('リマインダー登録上限に達しているため、時間指定のリマインダーを追加できませんでした');
                    }
                    if (!$reminder_time_result) {
                        $this->Flash->error('リマインダーを追加できませんでした');
                    }
                }
                if (!empty($this->request->data['Task']['reminder_datetimecheck'])) {
                    $reminder_datetime_result = $this->Reminder->addDatetimeReminder($this->Auth->user('id'), $result['Task']['id'], $this->request->data);
                    if (isset($reminder_datetime_result['limit'])) {
                        $this->Flash->error('リマインダー登録上限に達しているため、日時指定のリマインダーを追加できませんでした');
                    }
                    if (!$reminder_datetime_result) {
                        $this->Flash->error('リマインダーを追加できませんでした');
                    }
                }
            }

            // ルーチンを追加する場合はhistoryのルーチン総数を更新
            if ($this->request->data['Task']['tasktype_id'] === '1') {
                $this->History->updateTotal($this->Auth->user('id'), $this->date);
            }

            if (!$result === false) {
                $this->redirect('/tasks');
            }
        }

        $this->render('create');
    }

    /**
     * 既存のタスクを編集する
     */
    public function edit() {
        // パラメータからタスクIDを取得する
        $id = '';
        if (isset($this->request->params['pass'][0])){
            $id = $this->request->params['pass'][0];
        }
        $task = $this->Task->fetchTask($id, $this->Auth->user('id'));

        if (!$task) {
            throw new NotFoundException();
        }

        // 既存データの曜日情報を配列に展開
        $day = array();
        for ($i = 0; $i < 7; $i++) {
            if ($task['Task']["day${i}"] === true) {
                $day[] = $i;
            }
        }

        // 日付変更をチェックした日付を渡す
        $this->set('date', $this->date);
        // タスクタイプをビューに渡す
        $this->set('tasktype', $task['TaskType']['name']);
        // ビューに曜日の配列を渡す
        $this->set('day', $day);
        // メールアドレスフラグ
        $this->set('email_activation', $this->userdata['User']['email_activation']);
        // 登録ユーザーフラグ
        $this->set('register', $this->userdata['User']['register']);

        if ($this->request->is('post')) {
            // 削除のPOSTなら確認ページにリダイレクト
            if (isset($this->request->data['delete'])) {
                $this->redirect('/tasks/delete/' . $id);
            }

            // タスク登録数をチェック（タスクID改ざん時に新規登録になる可能性がある？ので）
            if ($this->Task->countTask($this->Auth->user('id')) > 1000) {
                $this->Flash->error('タスクの登録数が上限を超えています（上限1,000件）');
                $this->redirect('/tasks');
            }

            // 編集のPOSTなら
            // dayを先にバリデーション
            $this->Task->set($this->request->data);
            // dayが妥当ではないとき
            if (!$this->Task->validates(array('fieldList' => array('day')))) {
                $this->render('edit');
                return;
            }

            // 開始日が有効期限より遅いとき
            if ($this->request->data['Task']['expirecheck'] && isset($this->request->data['Task']['expire'])
                && $this->request->data['Task']['begin'] > $this->request->data['Task']['expire']
            ) {
                $this->Task->invalidate('expire', '有効期限は開始日以降の日付を指定してください');
                unset($this->request->data['Task']['expirecheck']);
                unset($this->request->data['Task']['expire']);
                $this->render('edit');
                return;
            }

            // リマインダーのバリデーション
            // 無効なPOSTデータを除去するためにオブジェクトを渡す
            if ($this->userdata['User']['email_activation']) {
                $error = $this->validateReminder($this->request);
                if ($error) {
                    $this->render('edit');
                    return;
                }
            }

            // ステータスが変更されていたら
            if ($task['Task']['status'] != $this->request->data['Task']['status']) {
                if ($this->request->data['Task']['status'] == 0) {
                    // 未完了に変えるとき
                    $this->request->data['Task']['lastdone'] = NULL;
                } else {
                    $this->request->data['Task']['lastdone'] = $this->date;
                }
            } else {
                $this->request->data['Task']['lastdone'] = $task['Task']['lastdone'];
            }

            // タスクデータを更新
            $result = $this->Task->editTask($id, $this->request->data);

            // リマインダーを更新
            if ($this->userdata['User']['email_activation']) {
                $reminder_time_result = $this->Reminder->editTimeReminder($this->Auth->user('id'), $result['Task']['id'], $this->request->data);
                if (isset($reminder_time_result['limit'])) {
                    $this->Flash->error('リマインダー登録上限に達しているため、時間指定のリマインダーを追加できませんでした');
                }
                if (!$reminder_time_result) {
                    $this->Flash->error('リマインダーを追加できませんでした');
                }
                $reminder_datetime_result = $this->Reminder->editDatetimeReminder($this->Auth->user('id'), $result['Task']['id'], $this->request->data);
                if (isset($reminder_datetime_result['limit'])) {
                    $this->Flash->error('リマインダー登録上限に達しているため、日時指定のリマインダーを追加できませんでした');
                }
                if (!$reminder_datetime_result) {
                    $this->Flash->error('リマインダーを追加できませんでした');
                }
            }

            if ($result) {
                $this->Flash->success('タスクを更新しました');
                $this->redirect('/tasks');
            } else {
                $this->Flash->error('更新に失敗しました');
                $this->redirect('/tasks');
            }
        }

        // フォームに既定値をセット
        $this->request->data['Task']['task_type_id'] = $task['Task']['task_type_id'];
        $this->request->data['Task']['title'] = $task['Task']['title'];
        $this->request->data['Task']['color'] = $task['Task']['color'];
        $this->request->data['Task']['body'] = $task['Task']['body'];
        $this->request->data['Task']['begin'] = $task['Task']['begin'];
        $this->request->data['Task']['expire'] = $task['Task']['expire'];
        $this->request->data['Task']['status'] = $task['Task']['status'];
        if ($task['Task']['expire'] !== '9999-12-31') {
            // チェックボックスにチェックをつける
            $this->request->data['Task']['expirecheck'] = array(0);
        } else {
            // 開始日の１ヶ月後と表示日の１ヶ月後の遅い方
            $this->request->data['Task']['expire'] = max(date('Y-m-d', strtotime($task['Task']['begin'] . ' +1 month')), date('Y-m-d', strtotime($this->date . ' +1 month')));
        }
        // リマインダー
        if (count($task['Reminder']) > 0) {
            foreach($task['Reminder'] as $r) {
                if ($r['status'] == 0) {
                    if ($r['type'] == 0) {
                        $this->request->data['Task']['reminder_timecheck'] = array(0);
                        $this->request->data['Task']['reminder_time']['hour'] = date('H', strtotime($r['time']));
                        $this->request->data['Task']['reminder_time']['min'] = date('i', strtotime($r['time']));
                    } else if (time() < strtotime($r['datetime'])) {
                        $this->request->data['Task']['reminder_datetimecheck'] = array(0);
                        $this->request->data['Task']['reminder_datetime']['year'] = date('Y', strtotime($r['datetime']));
                        $this->request->data['Task']['reminder_datetime']['month'] = date('m', strtotime($r['datetime']));
                        $this->request->data['Task']['reminder_datetime']['day'] = date('d', strtotime($r['datetime']));
                        $this->request->data['Task']['reminder_datetime']['hour'] = date('H', strtotime($r['datetime']));
                        $this->request->data['Task']['reminder_datetime']['min'] = date('i', strtotime($r['datetime']));
                    }
                }
            }
        }

        $this->render("edit");
    }

    /**
     * タスクを削除する
     */
    public function delete() {
        // パラメータからタスクIDを取得する
        $id = '';
        if (isset($this->request->params['pass'][0])){
            $id = $this->request->params['pass'][0];
        }
        $task = $this->Task->fetchTask($id, $this->Auth->user('id'));

        if (!$task) {
            throw new NotFoundException();
        }

        if ($this->request->is('post')) {
            if ($this->request->data['Task']['delete'] == true) {
                $result = $this->Task->delete($id);
            }

            if ($result) {
                // ルーチンを削除する場合はHistoryの総数を更新
                // 完了した当日のルーチンのときは総数を減らさない
                if ($task['Task']['task_type_id'] === '1' && $task['Task']['lastdone'] !== $this->date) {
                    $this->History->updateTotal($this->Auth->user('id'), $this->date);
                }

                $this->Flash->success('タスクを削除しました');
                $this->redirect('/tasks');
            } else {
                $this->Flash->error('タスクの削除ができませんでした');
                $this->redirect('/tasks');
            }
        }


        // 既存データの曜日情報を配列に展開
        $day = array();
        for ($i = 0; $i < 7; $i++) {
            if ($task['Task']["day${i}"] === true) {
                $day[] = $i;
            }
        }

        $this->set('day', $day);
        $this->set('task', $task);

        $this->render('delete');
    }

    // ajaxで更新ミスが起こったときの処理（現在未使用）
    public function error() {
        $this->autoRender = false;
        $this->Flash->error('更新に失敗しました');
        $this->redirect('/users/login');
    }

    /**
     * タスク登録、更新時のリマインダー部のバリデーション処理
     */
    private function validateReminder($request) {
        $error = false;

        $tmp = [];
        if (!empty($request->data['Task']['reminder_timecheck']) && isset($request->data['Task']['reminder_time'])) {
            $tmp['Reminder']['time'] = $request->data['Task']['reminder_time'];
        }
        if (!empty($request->data['Task']['reminder_datetimecheck']) && isset($request->data['Task']['reminder_datetime']['year'])) {
            $tmp['Reminder']['datetime'] = $request->data['Task']['reminder_datetime'];

            // リマインダーの日時が過去のとき
            $reminder_date = $request->data['Task']['reminder_datetime']['year'] . '-' .
                             $request->data['Task']['reminder_datetime']['month'] . '-' .
                             $request->data['Task']['reminder_datetime']['day'] . ' ' .
                             $request->data['Task']['reminder_datetime']['hour'] . ':' .
                             $request->data['Task']['reminder_datetime']['min'];
            if (time() > strtotime($reminder_date)) {
                $this->Task->invalidate('reminder_datetime', '過去の日時は指定できません');
                $error = true;
            }
        }

        $this->Reminder->set($tmp);
        $this->Reminder->validates(array('fieldList' => array('time', 'datetime')));

        foreach($this->Reminder->validationErrors as $field => $message_arr) {
            $this->Task->invalidate('reminder_' . $field, $message_arr[0]);
            unset($request->data['Task']['reminder_' . $field]);
            $error = true;
        }

        return $error;
    }

    /**
     * タスクをまとめて削除する
     *
     * @param array $postdata POSTの配列
     * @return boolean 結果
     */
    private function delete_collectively($postdata) {
        $task_count = count($postdata['Task']);
        $delete_tasks = [];

        if ($task_count > 20) {
            return false;
        }

        // チェックされたタスクを抽出
        forEach ($postdata['Task'] as $key => $value) {
            if ($value) {
                $delete_tasks[] = $key;
            }
        }

        return $this->Task->deleteAll(['Task.id' => $delete_tasks, 'Task.user_id' => $this->Auth->user('id')]);
    }
}
