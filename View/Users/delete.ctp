<?php
$this->assign('title', 'アカウント削除');
$this->Html->addCrumb('ユーザー情報変更', '/users/edit');
$this->Html->addCrumb('アカウント削除');
?>
<div>
    <div class="uk-alert uk-alert-warning c-alert">
        <p>アカウントを削除してもよろしいですか？</p>
    </div>
    <?php
    // タスク削除のフォーム
    echo $this->Form->create(
        'User',
        array('type' => 'post')
    );
    echo $this->Form->input(
        'User.delete',
        array(
            'type' => 'hidden',
            'value' => true
        )
    );
    echo $this->Form->button('アカウントを削除する', array(
        'type' => 'submit',
        'class' => 'uk-button c-button--danger'
    ));
    echo $this->Form->end();
    ?>
</div>
