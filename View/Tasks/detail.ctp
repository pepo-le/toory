<?php
$this->assign('title', 'タスク詳細');
?>
<div>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
        <?php echo $this->element('task_detail'); ?>
    </div>
    <?php echo $this->Html->link('タスクを編集する', '/tasks/edit/' . $task['Task']['id'], array('class' => 'uk-button uk-margin-right c-button--primary')); ?>
    <?php echo $this->Html->link('タスクを削除する', '/tasks/delete/' . $task['Task']['id'], array('class' => 'uk-button c-button--danger')); ?>
</div>
