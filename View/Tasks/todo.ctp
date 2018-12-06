<?php
switch($view_type) {
    case '':
        $this->assign('title', 'これからのTodo');
        $header = 'これからの';
        break;
    case 'done':
        $this->assign('title', '過去のTodo');
        $header = '過去の';
        break;
}
?>
<div>
    <?php echo $this->Form->create('Task', ['type' => 'post']); ?>
        <table id="task-table" class="uk-table p-task-table p-task-table--small-text p-task-table--list">
            <tr>
                <th class="uk-width-1-6 uk-text-center"><?php echo $this->Paginator->sort('begin', '開始日<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-2-6"><?php echo $this->Paginator->sort('title', 'タスク名<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)) ?></th>
                <th class="uk-width-1-6 uk-text-center uk-hidden-small"><?php echo $this->Paginator->sort('expire', '期限<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-1-6 uk-text-center uk-hidden-small"><?php echo $this->Paginator->sort('status', '状態<i class="fa fa-sort" aria-hidden="true"></i>', array('escape' => false)); ?></th>
                <th class="uk-width-1-6 uk-text-right p-task-table__delete-check">削除</th>
            </tr>
            <?php if(count($tasks_data) === 0): ?>
                <tr><td class="uk-text-center" colspan="4">Todoはありません</td></tr>
            <?php else: ?>
                <?php foreach ($tasks_data as $task): ?>
                <tr>
                    <td class="uk-width-1-6 uk-text-center"><?php echo h($task['Task']['begin']); ?></td>
                    <td class="uk-width-2-6"><?php echo $this->Html->link(h($task['Task']['title']), '/tasks/detail/' . $task['Task']['id']); ?><span class="c-task-color" style="background-color: <?php echo h($task['Task']['color']); ?>"></span></td>
                    <td class="uk-width-1-6 uk-text-center uk-hidden-small"><?php if ($task['Task']['expire'] !== '9999-12-31') echo h($task['Task']['expire']); ?></td>
                    <?php if ($task['Task']['status'] == 1): ?>
                        <td class="uk-width-1-6 uk-text-center uk-hidden-small">完了</td>
                    <?php else: ?>
                        <td class="uk-width-1-6 uk-text-center uk-hidden-small">未完了</td>
                    <?php endif; ?>
                    <td class="uk-width-1-6 uk-text-right p-task-table__delete-check">
                        <?php echo $this->Form->input('Task.' . $task['Task']['id'], [
                            'type' => 'checkbox',
                            'label' => '',
                            'checked' => false
                        ]); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <?php if(count($tasks_data) > 0): ?>
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
</div>
