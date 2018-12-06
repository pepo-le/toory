<?php
$this->assign('title', 'タスク削除');
$this->Html->addCrumb('タスク編集', '/tasks/edit');
$this->Html->addCrumb('タスク削除');
?>
<div>
    <div class="uk-alert uk-alert-warning c-alert">
        <p>以下のタスクを削除してもよろしいですか？</p>
    </div>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
        <?php echo $this->element('task_detail'); ?>
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
    echo $this->Form->button('タスクを削除する', array(
        'type' => 'submit',
        'class' => 'uk-button c-button--danger'
    ));
    echo $this->Form->end();
    ?>
</div>
