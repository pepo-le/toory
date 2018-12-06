<?php
$this->assign('title', '履歴の全削除');
$this->Html->addCrumb('履歴', '/histories/');
$this->Html->addCrumb('履歴の全削除');
?>
<div>
    <div class="uk-alert uk-alert-warning c-alert">
        <p>履歴を全て削除してもよろしいですか？</p>
    </div>
    <?php
    // タスク削除のフォーム
    echo $this->Form->create(
        'Task',
        array('type' => 'post')
    );
    echo $this->Form->input(
        'Task.delete',
        array(
            'type' => 'hidden',
            'value' => true
        )
    );
    echo $this->Form->button('履歴を全て削除する', array(
        'type' => 'submit',
        'class' => 'uk-button c-button--danger'
    ));
    echo $this->Form->end();
    ?>
</div>
