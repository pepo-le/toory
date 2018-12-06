<?php
$this->assign('title', 'リマインダー');
?>
<div>
    <?php echo $this->Form->create('Reminder', ['type' => 'post']); ?>
        <table id="task-table" class="uk-table p-task-table p-task-table--small-text p-task-table--list">
            <tr>
                <th class="uk-width-5-10 u-padding-left-2"><?php echo $this->Paginator->sort('begin', 'タスク名<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-2-10 uk-text-center uk-hidden-small"><?php echo $this->Paginator->sort('time', '通知時間<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-2-10 uk-text-center uk-hidden-small"><?php echo $this->Paginator->sort('datetime', '通知日時<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-1-10 uk-text-right p-task-table__delete-check">削除</th>
            </tr>
            <?php if(count($reminders) === 0): ?>
                <tr><td class="uk-text-center" colspan="3">リマインダーはありません</td></tr>
            <?php else: ?>
                <?php foreach ($reminders as $r): ?>
                <tr>
                    <td class="uk-width-5-10 u-padding-left-2"><?php echo $this->Html->link(h($r['Task']['title']), '/tasks/detail/' . h($r['Task']['id'])); ?><span class="c-task-color" style="background-color: <?php echo h($r['Task']['color']); ?>"></span></td>
                    <td class="uk-width-2-10 uk-text-center uk-hidden-small"><?php echo h($r['Reminder']['time']); ?></td>
                    <td class="uk-width-2-10 uk-text-center uk-hidden-small"><?php echo h($r['Reminder']['datetime']); ?></td>
                    <td class="uk-width-1-10 uk-text-right p-task-table__delete-check">
                        <?php echo $this->Form->input('Reminder.' . $r['Task']['id'] . '=' . $r['Reminder']['type'], [
                            'type' => 'checkbox',
                            'label' => '',
                            'checked' => false
                        ]); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <?php if(count($reminders) > 0): ?>
            <p class="p-delete-collectively-check">
                <label for="task-delete-all">Check all</label>
                <input type="checkbox" id="task-delete-all">
                <span>↑</span>
            </p>
            <p class="p-delete-collectively">チェックしたものをまとめて<input type="submit" value="削除" class="uk-button c-button--danger"></p>
        <?php endif; ?>
    <?php echo $this->Form->end(); ?>
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
    <p>リマインダーは20件まで登録できます。</p>
</div>
