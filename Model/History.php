<?php
// Taskモデルを読み込む
App::uses('Task','Model');
class History extends AppModel {
    public $Task;

    public $virtualFields = array(
        'year' => 'DATE_FORMAT(date, "%Y")',
        'month' => 'DATE_FORMAT(date, "%Y-%M")',
        'week' => 'DATE_FORMAT(date, "%u")'
    );

    public $validate = array(
        'done_todo' => array(
            'rule1' => [
                'rule' => ['range', -1, 100],
                'required' => false,
                'message' => '0から99までの数を入力してください'
            ]
        ),
        'done_routine' => array(
            'rule1' => [
                'rule' => ['range', -1, 100],
                'required' => false,
                'message' => '0から99までの数を入力してください'
            ]
        ),
        'total_routine' => array(
            'rule1' => [
                'rule' => ['range', -1, 100],
                'required' => false,
                'message' => '0から99までの数を入力してください'
            ]
        ),
    );

    /**
     * タスクの実行履歴を記録する
     *
     * 当日のhistoryがない場合は新規作成
     * あれば更新処理
     *
     * @param int $id ユーザーID
     * @param int $type タスクタイプID
     * @param date $date 日付変更確認後の日付
     * @return arrayまたはbool SQLの実行が適切に終わらなかった場合はfalseが返る
     */
    public function addHistory($id, $type, $date) {
        $this->Task = new Task();
        // historyテーブルの主キー（ID + 日付）
        $history_id = 'uid-' . $id . '_' . $date;

        // historyの作成・更新
        if ($this->exists($history_id)) {
            // 更新処理
            // タスクタイプの判定
            if ($type === '1') {
                // todoなら
                $data = array(
                    'done_todo' => "History.done_todo + 1"
                );

            } else {
                // routineなら
                // routineの総数を取得
                $total_routine = $this->Task->countRoutine($id, $date);

                $data = array(
                    'done_routine' => "History.done_routine + 1",
                    'total_routine' => $total_routine
                );
            }

            $result = $this->updateAll($data, array('History.id' => $history_id));
        } else {
            // 新規作成
            $data = array(
                'id' => $history_id,
                'user_id' => $id,
                'date' => $date
            );

            // タイプの判定
            if ($type === '1') {
                $data['done_todo'] = 1;
            } else {
                $data['done_routine'] = 1;
                // routineの総数を追加
                $data['total_routine'] = $this->Task->countRoutine($id, $date);
            }

            $this->create();
            $result = $this->save($data);
        }

        return $result;
    }

    /**
     * タスクの実行履歴を巻き戻し
     *
     * @param int $id ユーザーID
     * @param date $date 日付変更確認後の日付
     * @return bool SQLの実行結果（SQLが実行されれば該当レコードがなくてもtrueが返る）
     */
    public function rollbackHistory($id, $date, $type) {
        // historyテーブルの主キー（ID + 日付）
        $history_id = 'uid-' . $id . '_' . $date;

        // historyの存在確認
        if ($this->exists($history_id)) {
            // タスクタイプの判定
            if ($type === '1') {
                $data = array(
                    'done_todo' => "CASE WHEN History.done_todo > 0 THEN History.done_todo - 1 ELSE 0 END"
                );
            } else {
                $data = array(
                    'done_routine' => "CASE WHEN History.done_routine > 0 THEN History.done_routine - 1 ELSE 0 END"
                );
            }

            return $this->updateAll($data, array('History.id' => $history_id));
        }
    }

    /**
     * ルーチンの総数を更新する
     *
     * @param int $id ユーザーID
     * @param date $date 日付変更確認後の日付
     * @return bool 実行結果
     */
    public function updateTotal($id, $date) {
        $this->Task = new Task();
        // historyテーブルの主キー（ID + 日付）
        $history_id = 'uid-' . $id . '_' . $date;

        $data = array(
            'id' => $history_id,
            'user_id' => $id,
            'date' => $date
        );

        $data['total_routine'] = $this->Task->countRoutine($id, $date);

        return $this->save($data);
    }

    /**
     * 全てのHistoryを表示するPaginate用配列を返す
     *
     * @return array ページネーション用の配列
     */
    public function fetchAllHistories() {
        // ページネーション設定
        $options = array(
            'limit' => 15,
            'order' => array(
                'History.date' => 'DESC'
            )
        );

        return $options;
    }

    /**
     * 年間の合計履歴を表示するPaginate用配列を返す
     *
     * @return array ページネーション用の配列
     */
    public function fetchYearSum() {
        // sql_modeの変更
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $this->query($sql);

        $options = array(
            'limit' => 15,
            'order' => array(
                'date' => 'DESC'
            ),
            'fields' => array(
                'year',
                'sum(History.done_todo) as done_todo',
                'sum(History.done_routine) as done_routine',
                'sum(History.total_routine) as total_routine'
            ),
            'group' => 'year'
        );

        return $options;
    }

    /**
     * 月間の合計履歴を表示するPaginate用配列を返す
     *
     * @return array ページネーション用の配列
     */
    public function fetchMonthSum() {
        // sql_modeの変更
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $this->query($sql);

        $options = array(
            'limit' => 15,
            'order' => array(
                'date' => 'DESC'
            ),
            'fields' => array(
                'month',
                'sum(History.done_todo) as done_todo',
                'sum(History.done_routine) as done_routine',
                'sum(History.total_routine) as total_routine'
            ),
            'group' => 'month',
        );

        return $options;
    }

    /**
     * 週間の合計履歴を表示するPaginate用配列を返す
     *
     * @return array ページネーション用の配列
     */
    public function fetchWeekSum() {
        // sql_modeの変更
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $this->query($sql);

        $options = array(
            'limit' => 15,
            'order' => array(
                'date' => 'DESC'
            ),
            'fields' => array(
                'month',
                'week',
                'sum(History.done_todo) as done_todo',
                'sum(History.done_routine) as done_routine',
                'sum(History.total_routine) as total_routine'
            ),
            'group' => array(
                'month',
                'week'
            )
        );

        return $options;
    }

    /**
     * カレンダー用の月間historyデータを返す
     *
     * @param int $query_year クエリの年
     * @param int $query_month クエリの月
     * @param int user_id ユーザーID
     * @return array 指定月、指定ユーザーのhistoryデータ配列
     */
    public function monthHistory($year, $month, $user_id) {
        // 表示する月の朔日
        $request_date = date('Y-m-d', strtotime($year . '-' . $month . '-01'));
        // 表示する月の翌月の朔日
        $next_date = date('Y-m-d', strtotime('+1 month', strtotime($request_date)));

        // 表示月の朔日から翌月の朔日までの間（表示月のデータを取得）
        $data = array(
            'conditions' => array(
                'user_id' => $user_id,
                'date >=' => $request_date,
                'date <=' => $next_date,
            ),
            'order' => array(
                'date'
            )
        );

        return $this->find('all', $data);
    }

    /**
     * 指定期間のHistoryを返す
     *
     * @param int $page クエリのページ番号
     * @param int $user_id ユーザーID
     * @return array ページネーション用の配列
     */
    public function fetchPeriodHistories($start, $end, $user_id) {
        $data = array(
            'conditions' => array(
                'user_id' => $user_id,
                'date >' => $start,
                'date <=' => $end,
            ),
            'fields' => array(
                'date',
                'done_todo',
                'done_routine',
                'total_routine',
            ),
            'order' => array(
                'date'
            ),
        );

        return $this->find('all', $data);
    }

    /**
     * historyを一つ返す
     *
     * @param date $date 日付
     * @param int $user_id ユーザーID
     * @return array historyのデータ配列
     */
    public function fetchHistory($date, $user_id) {
        // historyテーブルの主キー（ID + 日付）
        $history_id = 'uid-' . $user_id . '_' . $date;

        $options = array(
            'conditions' => array(
                'id' => $history_id
            )
        );

        return $this->find('first', $options);
    }

    /**
     * Historyを編集する
     *
     * @param date $date 日付
     * @param int $user_id ユーザーID
     * @return array historyのデータ配列
     */
    public function editHistory($postdata, $user_id) {
        // historyテーブルの主キー（ID + 日付）
        $history_id = 'uid-' . $user_id . '_' . $postdata['History']['date'];

        $data = array(
            'id' => $history_id,
            'user_id' => $user_id,
            'date' => $postdata['History']['date'],
            'done_todo' => $postdata['History']['todo'],
            'done_routine' => $postdata['History']['routine'],
            'total_routine' => $postdata['History']['total']
        );

        return $this->save($data);
    }
}
