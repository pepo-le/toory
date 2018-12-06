<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Reminder', 'Model');

class ReminderShell extends AppShell {
    public function startup() {
    }

    public function sendReminders() {
        date_default_timezone_set('Asia/Tokyo');

        $reminder_model = new Reminder();
        $reminders_list = $reminder_model->fetchAllReminder(date('Y-m-d'));

        $email = new CakeEmail(Configure::read('email')['type']);
        $email->from(array(Configure::read('email')['address'] => 'Toory'));

        $ids = array();
        foreach($reminders_list as $reminder) {
            // 休暇モードのユーザーのリマインダーは無視
            if ($reminder['User']['vacation']) {
                continue;
            }

            date_default_timezone_set($reminder['User']['timezone']);
            $b_time = strtotime('-15 minutes', strtotime($reminder['Reminder']['time']));
            $a_time = strtotime('+15 minutes', strtotime($reminder['Reminder']['time']));
            $b_datetime = strtotime('-15 minutes', strtotime($reminder['Reminder']['datetime']));
            $a_datetime = strtotime('+15 minutes', strtotime($reminder['Reminder']['datetime']));

            // 日時指定リマインダー
            if ($reminder['Reminder']['type'] == 1 && time() > $b_datetime && time() < $a_datetime) {
                $ids[] = $reminder['Reminder']['id'];

                $email->to($reminder['User']['email']);
                $email->subject('通知: ' . $reminder['Task']['title'] . ' - ' . date('Y/m/d G時i分', strtotime($reminder['Reminder']['datetime'])));
                $body = "Tooryからのお知らせ\n\n";
                $body .= $reminder['User']['screenname'] . "さん\n\n";
                $body .= date('Y/m/d G時i分', strtotime($reminder['Reminder']['datetime'])) . "\n";
                $body .= $reminder['Task']['title'] . "の時間です。";
                if (!empty($reminder['Task']['body'])) {
                    $body .= "\n\n【詳細】\n";
                    $body .= $reminder['Task']['body'];
                }

                $mail_result = $email->send($body);
                if (!$mail_result) {
                    $this->log('Failed to send Reminder');
                }
            }

            // 時間指定リマインダー
            // 日付変更確認
            if (time() < strtotime($reminder['User']['changetime'])) {
                $date = date('Y-m-d', strtotime('-1 day'));
                $day = date('w', strtotime('-1 day'));
            } else {
                $date = date('Y-m-d');
                $day = date('w');
            }
            if ($reminder['Reminder']['type'] == 0 && $reminder['Task']['day' . $day]
                && $reminder['Task']['lastdone'] !== $date && time() > $b_time && time() < $a_time
            ) {
                $email->to($reminder['User']['email']);
                $email->subject('通知: ' . $reminder['Task']['title'] . ' - ' . date('G時i分', strtotime($reminder['Reminder']['time'])));
                $body = "Tooryからのお知らせ\n\n";
                $body .= $reminder['User']['screenname'] . "さん\n\n";
                $body .= date('G時i分', strtotime($reminder['Reminder']['time'])) . "\n";
                $body .= $reminder['Task']['title'] . "の時間です。";
                if (!empty($reminder['Task']['body'])) {
                    $body .= "\n\n【詳細】\n";
                    $body .= $reminder['Task']['body'];
                }

                $mail_result = $email->send($body);
                if (!$mail_result) {
                    $this->log('Failed to send Reminder');
                }
            }
        }

        // 日時指定通知のレコードを削除する
        if (!empty($ids)) {
            $result = $reminder_model->stopReminder($ids);
            if (!$result) {
                $this->log('Failed to update Reminder');
            }
        }
    }
}
