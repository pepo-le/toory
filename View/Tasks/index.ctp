<?php
$this->assign('title', '今日のタスク一覧');
$this->assign('css', '<link rel="stylesheet" type="text/css" href="/css/vendor/loaders.css">');
?>
<?php
// 今日のタスク数をカウント
$todo = 0;
$done_todo = 0;
$routine = 0;
$done_routine = 0;
foreach ($tasks_data as $row) {
    if ($row['Task']['task_type_id'] == 0) {
        if ($row['Task']['status'] == 1 && $row['Task']['lastdone'] === $date) {
            $done_todo = $done_todo + 1;
        } else if ($row['Task']['lastdone'] === NULL) {
            $todo = $todo + 1;
        }
    } else {
        if ($row['Task']['lastdone'] === $date) {
            $done_routine = $done_routine + 1;
        } else {
            $routine = $routine + 1;
        }

    }
}
?>
<section class="p-task">
    <h2>今日のTodo</h2>
    <table id="active-todo" class="uk-table p-task-table">
        <?php if ($todo === 0): ?>
            <tr class="task-table__none p-task-table__none"><td>今日のTodoはありません</td></tr>
        <?php else: ?>
            <?php foreach ($tasks_data as $row): ?>
            <?php
            // 未完了のTodoを表示
            if ($row['Task']['task_type_id'] == 0 && $row['Task']['status'] == 0):
            ?>
            <tr id=<?php echo 'task-' . h($row['Task']['id']); ?>>
                <td class="uk-width-1-10 uk-text-center p-task-table__check-td">
                    <?php
                    echo $this->Form->create('Task', array(
                        'url' => '/tasks/done',
                        'type' => 'post',
                        'id' => 'task-form-' . $row['Task']['id'],
                        'class'=> array('task-form', 'todo')
                    ));
                    echo $this->Form->hidden('Task.id', array(
                        'value' => h($row['Task']['id']),
                        'id' => 'task-form-id-' . $row['Task']['id'],
                        'class' => 'task-form-id'
                    ));
                    echo $this->Form->hidden('Task.task_type_id', array(
                        'value' => h($row['Task']['task_type_id']),
                        'class' => 'task-form-type'
                    ));
                    ?>
                    <div class="task-check p-task-check">
                        <input type="submit" id="task-check-<?php echo h($row['Task']['id']); ?>" class="task-check__input p-task-check__input">
                        <label class="p-task-check__label" for="task-check-<?php echo h($row['Task']['id']); ?>"><span class="p-task-check__checkmark c-loading c-loading--cover"><span class="p-task-check__loading-inner"></span></span></label>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </td>
                <td class="task-name">
                    <?php echo $this->Html->link(h($row['Task']['title']), '/tasks/detail/' . $row['Task']['id']) . '<span class="c-task-color" style="background-color: ' . h($row['Task']['color']) . ';"></span>' ?>
                </td>
                <td class="p-task-table__icon">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-ellipsis-v" aria-hidden="true"></i>',
                        '/tasks/detail/' . h($row['Task']['id']),
                        array('escape' => false)
                    );
                    ?>
                    <div class="p-task-table__icon__control">
                        <ul>
                            <li><?php echo $this->Html->link('詳細', '/tasks/detail/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('編集', '/tasks/edit/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('削除', '/tasks/delete/' . h($row['Task']['id'])); ?></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>

<section class="p-task">
    <h2>今日のルーチン</h2>
    <table id="active-routine" class="uk-table p-task-table">
        <?php if ($routine === 0): ?>
            <tr class="task-table__none p-task-table__none"><td>今日のTodoはありません</td></tr>
        <?php else: ?>
            <?php foreach ($tasks_data as $row): ?>
            <?php
            // 最終実行日が今日でないRoutineを表示
            if ($row['Task']['task_type_id'] == 1 && $row['Task']['lastdone'] !== $date):
            ?>
            <tr id=<?php echo 'task-' . h($row['Task']['id']); ?>>
                <td class="uk-width-1-10 uk-text-center p-task-table__check-td">
                    <?php
                    echo $this->Form->create('Task', array(
                        'url' => '/tasks/done',
                        'type' => 'post',
                        'id' => 'task-form-' . $row['Task']['id'],
                        'class'=> array('task-form', 'routine')
                    ));
                    echo $this->Form->hidden('Task.id', array(
                        'value' => h($row['Task']['id']),
                        'id' => 'task-form-id-' . $row['Task']['id'],
                        'class' => 'task-form-id'
                    ));
                    echo $this->Form->hidden('Task.task_type_id', array(
                        'value' => h($row['Task']['task_type_id']),
                        'class' => 'task-form-type'
                    ));
                    ?>
                    <div class="task-check">
                        <input type="submit" id="task-check-<?php echo h($row['Task']['id']); ?>" class="task-check__input p-task-check__input">
                        <label class="p-task-check__label" for="task-check-<?php echo h($row['Task']['id']); ?>"><span class="p-task-check__checkmark c-loading c-loading--cover"><span class="p-task-check__loading-inner"></span></span></label>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </td>
                <td class="task-name">
                    <?php echo $this->Html->link(h($row['Task']['title']), '/tasks/detail/' . $row['Task']['id']) . '<span class="c-task-color" style="background-color: ' . h($row['Task']['color']) . ';"></span>' ?>
                </td>
                <td class="p-task-table__icon">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-ellipsis-v" aria-hidden="true"></i>',
                        '/tasks/detail/' . h($row['Task']['id']),
                        array('escape' => false)
                    );
                    ?>
                    <div class="p-task-table__icon__control">
                        <ul>
                            <li><?php echo $this->Html->link('詳細', '/tasks/detail/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('編集', '/tasks/edit/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('削除', '/tasks/delete/' . h($row['Task']['id'])); ?></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>

<section class="p-task">
    <h2>今日完了したTodo</h2>
    <table id="done-todo" class="uk-table p-task-table">
        <?php if ($done_todo === 0): ?>
            <tr class="task-table__none p-task-table__none"><td>今日完了したTodoはありません</td></tr>
        <?php else: ?>
            <?php foreach ($tasks_data as $row): ?>
            <?php
            // 完了済みで最終実行日が今日のTodoを表示
            if ($row['Task']['task_type_id'] == 0 && $row['Task']['status'] == 1 && $row['Task']['lastdone'] === $date):
            ?>
            <tr id=<?php echo 'task-' . h($row['Task']['id']); ?>>
                <td class="uk-width-1-10 uk-text-center p-task-table__check-td">
                    <?php
                    // タスクIDとタイプのForm
                    echo $this->Form->create('Task', array(
                        'url' => '/tasks/cancel',
                        'type' => 'post',
                        'id' => 'task-form-' . $row['Task']['id'],
                        'class'=> array('task-form', 'todo')));
                    echo $this->Form->hidden('Task.id', array(
                        'value' => h($row['Task']['id']),
                        'id' => 'task-form-id-' . $row['Task']['id'],
                        'class' => 'task-form-id'
                    ));
                    echo $this->Form->hidden('Task.task_type_id', array(
                        'value' => h($row['Task']['task_type_id']),
                        'class' => 'task-form-type'
                    ));
                    ?>
                    <div class="task-check">
                        <input type="submit" id="task-check-<?php echo h($row['Task']['id']); ?>" class="task-check__input p-task-check__input checked">
                        <label class="p-task-check__label" for="task-check-<?php echo h($row['Task']['id']); ?>"><span class="p-task-check__checkmark c-loading c-loading--cover"><span class="p-task-check__loading-inner"></span></span></label>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </td>
                <td class="task-name">
                    <?php echo $this->Html->link(h($row['Task']['title']), '/tasks/detail/' . $row['Task']['id']) . '<span class="c-task-color" style="background-color: ' . h($row['Task']['color']) . ';"></span>' ?>
                </td>
                <td class="p-task-table__icon">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-ellipsis-v" aria-hidden="true"></i>',
                        '/tasks/detail/' . h($row['Task']['id']),
                        array('escape' => false)
                    );
                    ?>
                    <div class="p-task-table__icon__control">
                        <ul>
                            <li><?php echo $this->Html->link('詳細', '/tasks/detail/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('編集', '/tasks/edit/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('削除', '/tasks/delete/' . h($row['Task']['id'])); ?></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>

<section class="p-task">
    <h2>今日完了したルーチン</h2>
    <table id="done-routine" class="uk-table p-task-table">
        <?php if ($done_routine === 0): ?>
            <tr class="task-table__none p-task-table__none"><td>今日完了したルーチンはありません</td></tr>
        <?php else: ?>
            <?php foreach ($tasks_data as $row): ?>
            <?php
            // 最終実行日が今日のRoutineを表示
            if ($row['Task']['task_type_id'] == 1 && $row['Task']['lastdone'] === $date):
            ?>
            <tr id=<?php echo 'task-' . h($row['Task']['id']); ?>>
                <td class="uk-width-1-10 uk-text-center p-task-table__check-td">
                    <?php
                    // タスクIDとタイプのForm
                    echo $this->Form->create('Task', array(
                        'url' => '/tasks/cancel',
                        'type' => 'post',
                        'id' => 'task-form-' . $row['Task']['id'],
                        'class'=> array('task-form', 'routine')));
                    echo $this->Form->hidden('Task.id', array(
                        'value' => h($row['Task']['id']),
                        'id' => 'task-form-id-' . $row['Task']['id'],
                        'class' => 'task-form-id'
                    ));
                    echo $this->Form->hidden('Task.task_type_id', array(
                        'value' => h($row['Task']['task_type_id']),
                        'class' => 'task-form-type'
                    ));
                    ?>
                    <div class="task-check">
                        <input type="submit" id="task-check-<?php echo h($row['Task']['id']); ?>" class="task-check__input p-task-check__input checked">
                        <label class="p-task-check__label" for="task-check-<?php echo h($row['Task']['id']); ?>"><span class="p-task-check__checkmark c-loading c-loading--cover"><span class="p-task-check__loading-inner"></span></span></label>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </td>
                <td class="task-name">
                    <?php echo $this->Html->link(h($row['Task']['title']), '/tasks/detail/' . $row['Task']['id']) . '<span class="c-task-color" style="background-color: ' . h($row['Task']['color']) . ';"></span>' ?>
                </td>
                <td class="p-task-table__icon">
                    <?php
                    echo $this->Html->link(
                        '<i class="fa fa-ellipsis-v" aria-hidden="true"></i>',
                        '/tasks/detail/' . h($row['Task']['id']),
                        array('escape' => false)
                    );
                    ?>
                    <div class="p-task-table__icon__control">
                        <ul>
                            <li><?php echo $this->Html->link('詳細', '/tasks/detail/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('編集', '/tasks/edit/' . h($row['Task']['id'])); ?></li>
                            <li><?php echo $this->Html->link('削除', '/tasks/delete/' . h($row['Task']['id'])); ?></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</section>
