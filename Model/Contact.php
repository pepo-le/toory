<?php
class Contact extends AppModel {
    public $validate = array(
        'name' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'お名前を入力してください'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 250),
                'message' => 'お名前は250文字以内で入力してください'
            )
        ),
        'email' => array(
            'rule1' => array(
                'rule' => 'email',
                'allowEmpty' => true,
                'message' => 'メールアドレスの形式ではありません'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 255),
                'message' => 'メールアドレスは255文字以内で入力してください'
            ),
        ),
        'body' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'お問い合わせ内容を入力してください'
            ),
            'rule2' => array(
                'rule' => array('maxLength', 3000),
                'message' => 'お問い合わせ内容は3000文字以内で入力してください',
            )
        )
    );

    /**
     * お問い合わせ内容をデータベースに保存
     *
     * @param array $data Postのデータ
     * @return array 保存した配列
     */
    public function saveContact($data) {
        $data = array(
            'name' => $data['Contact']['name'],
            'email' => $data['Contact']['email'],
            'body' => $data['Contact']['body']
        );

        $this->create();
        return $this->save($data);
    }
}
