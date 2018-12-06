<?php
class LogicHistoriesComponent extends Component {
    /**
     * タスクの履歴を含めたカレンダーのHTMLを返す
     *
     * @param int $year 表示する年
     * @param int $month 表示する月
     * @param array $histories_表示する月のhistoryデータ
     * @return string カレンダーのHTML文字列
     */
    public function historiesCalendar($year, $month, $day, $offset_date, $histories_data) {
        // 月移動のリンク
        $today_link = Router::url('/') . 'histories';
        $prev_link = Router::url('/') . 'histories?year=' .
            date('Y', strtotime('-1 month', strtotime($year . '-' . $month . '-01'))) .
            '&month=' . date('m', strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
        $next_link = Router::url('/') . 'histories?year=' .
            date('Y', strtotime('+1 month', strtotime($year . '-' . $month . '-01'))) .
            '&month=' . date('m', strtotime('+1 month', strtotime($year . '-' . $month . '-01')));
        //月末の取得
        $l_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
        //初期出力
        $html = <<<EOM
<table id="calendar" class="uk-table p-calendar__table">
    <thead>
        <tr>
            <td colspan="7" class="p-calendar__table__header">
                <span>{$year}年{$month}月</span><a href="$prev_link" id="prev-month" class="p-calendar__table__header__arrow p-calendar__table__header__arrow--left"><i class="fa fa-angle-left" aria-hidden="true"></i></a><a href="$next_link" id="next-month" class="p-calendar__table__header__arrow p-calendar__table__header__arrow--right"><i class="fa fa-angle-right" aria-hidden="true"></i></a><a href="$today_link" class="uk-button p-calendar__table__header__today-link">今日</a>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="uk-text-center p-calendar__td--sun">日</th>
            <th class="uk-text-center">月</th>
            <th class="uk-text-center">火</th>
            <th class="uk-text-center">水</th>
            <th class="uk-text-center">木</th>
            <th class="uk-text-center">金</th>
            <th class="uk-text-center p-calendar__td--sat">土</th>
        </tr>\n
EOM;

        // 週数
        $lc = 0;
        // タスクデータのイテレータ変数
        $history_i = 0;

        $now = time();
        $is_future = false;
        // 月の日数分繰り返す
        for ($i = 1; $i < $l_day + 1;$i++) {
            $classes = array();
            $class  = '';
            if (strtotime($year . '-' . $month . '-'. $i) > $now) {
                $is_future = true;
            }

            // 曜日の取得
            $week = date('w', mktime(0, 0, 0, $month, $i, $year));

            // 曜日が日曜日の場合は行挿入
            if ($week == 0) {
                $html .= "\t\t<tr>\n";
                $lc++;
            }

            // 朔日の場合
            if ($i == 1) {
                if($week != 0) {
                    $html .= "\t\t<tr>\n";
                    $lc++;
                }
                // 朔日の曜日までマスを埋める
                $html .= $this->repeatEmptyTd($week);
            }

            // 土曜日と日曜日はclassをつける
            if ($week == 6) {
                $classes[] = 'sat';
            } else if ($week == 0) {
                $classes[] = 'sun';
            }

            // 日付の全てのセルにクラスをつける
            $classes[] = ' p-calendar__table__day-cell';

            // 今日の日付の場合はclassをつける
            if ($i == $day && $year == date('Y', strtotime($offset_date)) && $month == date('n', strtotime($offset_date))) {
                $classes[] = 'p-calendar__table__day-cell--today';
            }

            // 日のセルを追加
            if ($is_future) {
                $day_data = '';
            } else {
                $day_data = '<a href="' . Router::url('/histories/edit?date=', true) . $year . '-' . $month . '-' . str_pad($i, 2, 0, STR_PAD_LEFT) . '">';
            }
            $day_data .= '<div class="p-calendar__table__day-cell__left">';
            $day_data .= '<span class="p-calendar__table__day">' . $i . '</span>';
            $day_data .= '</div>';
            $day_data .= '<div class="p-calendar__table__day-cell__right">';
            $date = $year . '-' . $month . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
            // 表示済みのhistory番号がhistories総数より少ない時
            while ($history_i < count($histories_data)) {
                // 日付が選択中のhistoryのdateより前のときはループを抜ける
                if (strtotime($date) < strtotime($histories_data[$history_i]['History']['date'])) {
                    break;
                }
                $done_todo = $histories_data[$history_i]['History']['done_todo'];
                $done_routine = $histories_data[$history_i]['History']['done_routine'];
                $total_routine = $histories_data[$history_i]['History']['total_routine'];
                if (+$total_routine !== 0) {
                    $routine_rate = round($done_routine / $total_routine * 100);
                } else {
                    $routine_rate = '-';
                }
                // 日付と選択中のhistoryのdateが一致するときは日付にデータを追加
                if ($date === $histories_data[$history_i]['History']['date']) {

                    $day_data .= '<i class="fa fa-check-square-o p-calendar__table__day-icon" aria-hidden="true"></i><span class="p-calendar__table__day-text">Task: </span>' . $done_todo . '<br />' .
                        '<i class="fa fa-circle-o-notch p-calendar__table__day-icon" aria-hidden="true"></i><span class="p-calendar__table__day-text">Routine: </span>' . $done_routine . '/' . $total_routine . '<br />' .
                        '<i class="fa fa-percent p-calendar__table__day-icon" aria-hidden="true"></i><span class="p-calendar__table__day-text">Rate: </span>' . $routine_rate . '%';
                    // ルーチン実行率によりclassをつける
                    if ($routine_rate == 100) {
                        $classes[] = 'p-calendar__td--rate100';
                    } else if ($routine_rate >= 50) {
                        $classes[] = 'p-calendar__td--rate50';
                    } else if ($routine_rate > 0) {
                        $classes[] = 'p-calendar__td--rate1';
                    } else {
                        $classes[] = 'p-calendar__td--rate0';
                    }
                    break;
                }
                $history_i = $history_i + 1;
            }
            $day_data .= '</div>';
            if ($is_future) {
                $day_data .= '</a>';
            }

            // classを指定
            if (count($classes) > 0) {
                $class = ' class="'.implode(' ', $classes).'"';
            }

            // tdタグを追加
            $html .= "\t\t\t".'<td'.$class.'>'.
                $day_data ."\n";
            // 月末の場合は残りのマスを埋める
            if ($i == $l_day) {
                $html .= $this->repeatEmptyTd(6 - $week);
            }
            // 土曜日の場合は改行
            if ($week == 6) {
                $html .= "\t\t</tr>\n";
            }
        }

        if ($lc < 5) {
            $html .= "\t\t<tr>\n";
            $html .= $this->repeatEmptyTd(7);
            $html .= "\t\t</tr>\n";
        }

        if ($lc == 4) {
            $html .= "\t\t<tr>\n";
            $html .= $this->repeatEmptyTd(7);
            $html .= "\t\t</tr>\n";
        }

        $html .= "\t</tbody>\n";
        $html .= "</table>\n";

        // HTMLを返す
        return $html;
    }

    /**
     * 指定回数だけ空白のtdタグを繰り返してHTMLを返す
     *
     * @param int $n 繰り返し回数
     * @return string HTMLの文字列
     */
    private function repeatEmptyTd($n = 0) {
        return str_repeat("\t\t<td class=\"p-calendar__table__day-cell\"> </td>\n", $n);
    }
}
?>
