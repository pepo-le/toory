<?php
App::uses('CakeEmail', 'Network/Email');

class ContactsController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index() {
        if ($this->request->is('post')) {
            $email = new CakeEmail('gmail');

            $email->from(array('ab123yz789@gmail.com' => '問い合わせフォーム'));
            $email->to('ab123yz789@gmail.com');
            $email->subject('問い合わせフォームから');

            $body = 'お名前：' . h($this->request->data['Contact']['name']) . "\n";
            $body .= 'メールアドレス：' . h($this->request->data['Contact']['email']) . "\n\n";
            $body .= 'お問い合わせ内容：' . "\n" . h($this->request->data['Contact']['body']);

            $mail_result = $email->send($body);

            if ($mail_result) {
                $this->Flash->success('送信しました');
            } else {
                $this->Flash->error('送信できませんでした');
            }

            $this->Contact->saveContact($this->request->data);

            $this->redirect('/contact');
        }

        $this->render('index');
    }
}
