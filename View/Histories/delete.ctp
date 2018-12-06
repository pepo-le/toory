<?php
$this->assign('title', '履歴の削除');
$this->Html->addCrumb('履歴', '/histories/');
$this->Html->addCrumb('履歴の削除');
?>
<div>
    <div class="uk-alert uk-alert-warning c-alert">
        <p>以下の履歴を削除してもよろしいですか？</p>
    </div>
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
        <dl class="uk-description-list-horizontal c-task-detail">
            <dt>日付：</dt>
            <dd><?php echo h($history_data['History']['date']); ?></dd>
            <dt>Todo実行数：</dt>
            <dd><?php echo h($history_data['History']['done_todo']); ?></dd>
            <dt>ルーチン実行数：</dt>
            <dd><?php echo h($history_data['History']['done_routine']); ?></dd>
            <dt>ルーチン総数：</dt>
            <dd><?php echo h($history_data['History']['total_routine']); ?></dd>
            <dt>ルーチン実行率：</dt>
            <?php if ($history_data['History']['total_routine'] == 0): ?>
                <dd>-</dd>
            <?php else: ?>
                <dd><?php echo round((int)$history_data['History']['done_routine'] / (int)$history_data['History']['total_routine'] * 100) . '%'; ?></dd>
            <?php endif; ?>
        </dl>
    </div>
    <?php
    // 履歴削除のフォーム
    echo $this->Form->create(
        'History',
        array('type' => 'post')
    );
    echo $this->Form->input(
        'History.delete',
        array(
            'type' => 'hidden',
            'value' => true
        )
    );
    echo $this->Form->button('履歴を削除する', array(
        'type' => 'submit',
        'class' => 'uk-button c-button--danger'
    ));
    echo $this->Form->end();
    ?>
</div>
