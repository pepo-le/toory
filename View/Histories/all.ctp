<?php
$this->assign('title', '履歴一覧');
$this->Html->addCrumb('履歴', '/histories/');
$this->Html->addCrumb('履歴一覧');
?>
<div>
    <table class="uk-table p-task-table p-task-table--small-text p-task-table--center">
        <tr>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner"><?php echo $this->Paginator->sort('date', '日付<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></div></th>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner"><?php echo $this->Paginator->sort('done_todo', 'Todo実行数<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></div></th>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner"><?php echo $this->Paginator->sort('done_routine', 'ルーチン実行数<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></div></th>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner"><?php echo $this->Paginator->sort('total_routine', 'ルーチン総数<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></div></th>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner">ルーチン実行率</div></th>
            <th class="uk-width-1-6 uk-text-center p-history-header"><div class="p-history-header__inner">編集</div></th>
        </tr>
        <?php if(count($history_data) === 0): ?>
            <tr><td class="uk-text-center" colspan="6">履歴はありません</td></tr>
        <?php else: ?>
            <?php foreach($history_data as $history): ?>
            <tr>
                <td class="uk-width-1-6"><?php echo date('Y/m/d', strtotime(h($history['History']['date']))); ?></td>
                <td class="uk-width-1-6"><?php echo h($history['History']['done_todo']); ?></td>
                <td class="uk-width-1-6"><?php echo h($history['History']['done_routine']);
                 ?>
                </td>
                <td class="uk-width-1-6"><?php echo h($history['History']['total_routine']); ?></td>
                <?php if ($history['History']['total_routine'] == 0): ?>
                    <td class="uk-width-1-6">-</td>
                <?php else: ?>
                    <td class="uk-width-1-6"><?php echo round((int)$history['History']['done_routine'] / (int)$history['History']['total_routine'] * 100) . '%'; ?></td>
                <?php endif; ?>
                <td class="uk-width-1-6">
                    <?php echo $this->Html->link('<i class="fa fa-edit" aria-hidden="true"></i>', '/histories/edit?date=' . h($history['History']['date']) . '&ref=all', array('escape' => false)); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <ul class="uk-pagination">
        <?php
        echo $this->Paginator->numbers(array(
            'currentClass' => 'uk-active',
            'currentTag' => 'span',
            'tag' => 'li',
            'modulus' => 4,
            'separator' => '',
            'first' => '<<<',
            'last' => '>>>'
        ));
        ?>
    </ul>
</div>
