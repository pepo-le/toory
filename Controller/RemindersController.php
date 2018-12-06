<?php
class RemindersController extends AppController {
    public $uses = array('Reminder');

    public $components = array('Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        // ログインしていないときはログインページにリダイレクト
        $this->Auth->deny();
    }

    /**
     * リマインダーの一覧を表示
     */
    public function index() {
        // まとめてタスクを削除
        if ($this->request->is('post')) {
            $result = $this->delete_collectively($this->request->data);

            // 削除できた場合は空の配列が返るので処理が逆になる
            if ($result) {
                $this->Flash->error('リマインダーを削除できませんでした');
            } else {
                $this->Flash->success('リマインダーを削除しました');
            }
        }

        $this->paginate = $this->Reminder->fetchReminder($this->Auth->user('id'), $this->date);
        $reminders = $this->paginate('Reminder');
        $this->set('reminders', $reminders);
    }

    /**
     * タスクをまとめて削除する
     *
     * @param array $postdata POSTの配列
     * @return boolean 結果
     */
    private function delete_collectively($postdata) {
        $reminder_count = count($postdata['Reminder']);
        if ($reminder_count > 20) {
            return false;
        }

        $delete_reminders = [];
        $delete_reminders_types = [];
        // チェックされたタスクを抽出
        forEach ($postdata['Reminder'] as $key => $value) {
            $arr = explode('=', $key);

            if ($value) {
                $delete_reminders[] = $arr[0];
                $delete_reminders_types[] = $arr[1];
            }
        }

        // 削除対象が無いとき
        if (count($delete_reminders) === 0 || count($delete_reminders_types) === 0) {
            return false;
        }

        // 複合キーのINは IN ((?, ?), (?, ?), (?, ?), ...)
        $sql = 'DELETE FROM reminders WHERE (reminders.task_id, reminders.type) IN (';
        for ($i = 0; $i < count($delete_reminders); $i++) {
            $sql .= '(?, ?),';
        }

        $sql = substr($sql, 0, -1);
        // ユーザーを確認
        $sql .= ') and reminders.user_id = ?;';

        $params = [];
        for ($i = 0; $i < count($delete_reminders); $i++) {
            $params[] = $delete_reminders[$i];
            $params[] = $delete_reminders_types[$i];
        }

        $params[] = $this->Auth->user('id');

       return $this->Reminder->query($sql, $params, false);
    }
}
