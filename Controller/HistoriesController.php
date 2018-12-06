<?php
class HistoriesController extends AppController {
    public $uses = array('Task', 'TaskType', 'History');

    public $components = array('Paginator', 'LogicHistories');

    public function beforeFilter() {
        parent::beforeFilter();
        // ログインしていないときはログインページにリダイレクト
        $this->Auth->deny();
        $this->Auth->allow('period');
    }

    /**
     * 実行履歴カレンダー表示
     *
     * デフォルトでは当日の月を表示
     * クエリで年、月をしていすることで表示月を変更する
     * コンポーネントからHTMLを取得し、ajaxのレスポンスとして返す、またはViewに渡す
     *
     * @query int year 指定年
     * @query int month 指定月
     */
    public function index() {
        $year = date('Y', strtotime($this->date));
        $month = date('m', strtotime($this->date));
        $day = date('d', strtotime($this->date));

        // 日付のクエリがあるとき
        if (isset($this->request->query['year'])
            && ctype_digit($this->request->query['year'])
            && $this->request->query['year'] > 2010
            && $this->request->query['year'] < 3000
        ) {
            $year = $this->request->query['year'];
        }

        if (isset($this->request->query['month'])
            && ctype_digit($this->request->query['month'])
            && $this->request->query['month'] > 0
            && $this->request->query['month'] <= 12
        ) {
            $month = $this->request->query['month'];
        }

        // 表示月のhistoriesデータを取得
        $histories_data = $this->History->monthHistory($year, $month, $this->Auth->user('id'));

        // historiesデータ込のカレンダーHTMLを取得
        $histories_table = $this->LogicHistories->historiesCalendar($year, $month, $day, $this->date, $histories_data);

        // ajaxのとき
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            return $histories_table;
        }

        $this->set('histories_table', $histories_table);
        $this->set('year', $year);
        $this->set('month', $month);

        $this->render('calendar');
    }

    /**
     * タスクの実行履歴を全て表示
     */
    public function all() {
        // ページネーション設定
        $this->paginate = $this->History->fetchAllHistories();

        $conditionss = array(
            'History.user_id' => $this->Auth->user('id')
        );

        // historyを取得
        $history_data =  $this->Paginator->paginate('History', $conditionss);

        // historyをViewに渡す
        $this->set('history_data', $history_data);
        $this->render('all');
    }

    /**
     * 年間のルーチン履歴を表示
     */
    public function year() {
        // ページネーション設定
        $this->paginate = $this->History->fetchYearSum();

        $conditions = array(
            'History.user_id' => $this->Auth->user('id'),
        );
        // historyの取得
        $history_data = $this->Paginator->paginate('History', $conditions);

        // historyをViewに渡す
        $this->set('history_data', $history_data);
        $this->render('year');
    }

    /**
     * 月間のルーチン履歴を表示
     */
    public function month() {
        $this->paginate = $this->History->fetchMonthSum();

        $conditions = array(
            'History.user_id' => $this->Auth->user('id'),
        );
        // historyの取得
        $history_data = $this->Paginator->paginate('History', $conditions);

        // historyをViewに渡す
        $this->set('history_data', $history_data);
        $this->render('month');
    }

    /**
     * 週間のルーチン履歴を表示
     */
    public function week() {
        $this->paginate = $this->History->fetchWeekSum();

        $conditions = array(
            'History.user_id' => $this->Auth->user('id'),
        );
        // historyの取得
        $history_data = $this->Paginator->paginate('History', $conditions);

        // historyをViewに渡す
        $this->set('history_data', $history_data);
        $this->render('week');
    }

    /**
     * 1ヶ月の履歴をグラフ表示する
     */
    public function graph() {
        $this->render('graph');
    }

    /**
     * 1ヶ月の履歴をJSONで返すAPI
     */
    public function period() {
        $this->autoRender = false;
        $this->autoLayout = false;

        if (!$this->request->is('ajax')) {
            $this->redirect('/users/login');
        }

        if (!$this->Auth->loggedIn()) {
            $this->response->type('application/json');
            $res = [];
            $res['errors'] = [
                'code' => 215,
                'message' => 'Bad Authentication data.'
            ];
            $this->response->body(json_encode($res));
            return;
        }

        $page = 1;
        $period = 10;

        // ページのクエリがあるとき
        if (isset($this->request->query['page'])
            && ctype_digit($this->request->query['page'])
            && $this->request->query['page'] > 1
            && $this->request->query['page'] < 5000
        ) {
            $page = $this->request->query['page'];
        }

        // 期間のクエリがあるとき
        if (isset($this->request->query['period'])
            && ctype_digit($this->request->query['period'])
            && $this->request->query['period'] > 2
            && $this->request->query['period'] < 31
        ) {
            $period = $this->request->query['period'];
        }

        // 取得するデータの初日と最終日
        $start_day = date('Y-m-d', strtotime(-($period * $page) . "days"));
        $end_day = date('Y-m-d', strtotime(-($period * ($page - 1)) . "days"));

        // 表示期間のhistoriesデータを取得
        $histories_data = $this->History->fetchPeriodHistories($start_day, $end_day, $this->Auth->user('id'));

        $this->response->type('application/json');
        $this->response->body(json_encode($histories_data));
        return;
    }

    /**
     * 履歴を全て削除する
     */
    public function delete_all() {
        if ($this->request->is('post')) {
            $result = $this->History->deleteAll(array(
                'user_id' => $this->Auth->user('id')
            ));

            if ($result) {
                $this->Flash->success('履歴を全て削除しました');
                $this->redirect('/tasks');
            } else {
                $this->Flash->error('履歴の削除に失敗しました');
                $this->redirect('/tasks');
            }
        }

        $this->render('delete_all');
    }

    /**
     * 履歴を編集する
     */
    public function edit() {
        // クエリから日時を取得する
        $querydate = '';
        if (isset($this->request->query['date']) && $querytime = strtotime($this->request->query['date'])) {
            if ($querytime > time() || $querytime < strtotime('2015-01-01')) {
                throw new NotFoundException();
            }
            $querydate = date('Y-m-d', $querytime);
        } else {
            throw new NotFoundException();
        }

        $history_data = $this->History->fetchHistory($querydate, $this->Auth->user('id'));

        if ($this->request->is('post')) {
            // 削除のPOSTならリダイレクト
            if (isset($this->request->data['delete'])) {
                $this->redirect('/histories/delete/' . $querydate);
            }

            if (isset($this->request->data['History']['date']) && $querytime = strtotime($this->request->data['History']['date'])) {
                if ($querytime > time() || $querytime < strtotime('2015-01-01')) {
                    throw new NotFoundException();
                }
            }

            if ($this->request->data['History']['routine'] > $this->request->data['History']['total']) {
                $this->History->invalidate('total', '総数は実行数より多い数をしていてください');
                return $this->render('edit');
            }

            $result = $this->History->editHistory($this->request->data, $this->Auth->user('id'));

            if ($result) {
                $this->Flash->success('履歴を更新しました');
                if (isset($this->request->query['ref'])) {
                    $this->redirect($this->request->query['ref']);
                } else {
                    $this->redirect('/histories/');
                }
            } else {
                $this->Flash->error('履歴を更新できませんでした');
                $this->redirect('/histories/');
            }
        }

        // フォームに既定値をセット
        $this->request->data['History']['date'] = $querydate;
        if ($history_data) {
            $this->request->data['History']['todo'] = $history_data['History']['done_todo'];
            $this->request->data['History']['routine'] = $history_data['History']['done_routine'];
            $this->request->data['History']['total'] = $history_data['History']['total_routine'];
        } else {
            $this->request->data['History']['todo'] = 0;
            $this->request->data['History']['routine'] = 0;
            $this->request->data['History']['total'] = 0;

        }

        // リファラをセット
        $this->referer = $this->referer('/histories/');

        $this->render('edit');
    }

    /**
     * 一日の履歴を削除する
     */
    public function delete() {
        // パラメータからタスクIDを取得する
        $date = '';
        if (isset($this->request->params['pass'][0])){
            $date = $this->request->params['pass'][0];
        } else {
            throw new NotFoundException();
        }

        $history_data = $this->History->fetchHistory($date, $this->Auth->user('id'));

        if (!$history_data) {
            throw new NotFoundException();
        }

        if ($this->request->is('post')) {
            if ($this->request->data['History']['delete'] == true) {
                $result = $this->History->delete($history_data['History']['id']);

                if ($result) {
                    $this->Flash->success('履歴を削除しました');
                    $this->redirect('/histories');
                } else {
                    $this->Flash->error('履歴を削除できませんでした');
                    $this->redirect('/histories');
                }
            }
        }

        $this->set('history_data', $history_data);

        $this->render('delete');
    }
}
