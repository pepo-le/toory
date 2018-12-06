<dl class="uk-description-list-horizontal p-task-detail">
    <dt>タスク名：</dt>
    <dd><?php echo h($task['Task']['title']); ?></dd>
    <dt>種類：</dt>
    <dd><?php echo h($task['TaskType']['name']); ?></dd>
    <dt>タスクカラー：</dt>
    <dd><span class="p-task-detail__color" style="background-color: <?php echo h($task['Task']['color']); ?>"></span> <?php echo h($task['Task']['color']); ?></dd>
    <?php if ($task['Task']['task_type_id'] == 1): ?>
        <dt>状態：</dt>
        <?php if ($task['Task']['status'] == 1): ?>
        <dd>完了済み</dd>
        <?php else: ?>
        <dd>未完了</dd>
        <?php endif; ?>
    <?php endif; ?>
    <dt>詳細：</dt>
    <dd><?php if (!empty($task['Task']['body'])) { echo h($task['Task']['body']); } else { echo '&nbsp;'; } ?></dd>
    <dt>有効な曜日：</dt>
    <?php if (count($day) === 7): ?>
        <dd>毎日</dd>
    <?php else: ?>
        <?php
            $weekday = array( "日", "月", "火", "水", "木", "金", "土" );
            $days = '';
            foreach($day as $d) { $days .= $weekday[$d] . '曜日, '; }
        ?>
        <dd><?php echo h(mb_substr($days, 0, -2)); ?></dd>
    <?php endif; ?>
    <dt>開始日：</dt>
    <dd><?php echo h($task['Task']['begin']); ?></dd>
    <dt>有効期限：</dt>
    <dd>
    <?php
        if ($task['Task']['expire'] !== '9999-12-31') {
            echo h($task['Task']['expire']);
        } else {
            echo '&nbsp;';
        }
    ?>
    </dd>
    <?php if ($task['Task']['task_type_id'] == 2): ?>
        <dt>最終実行日：</dt>
        <dd>
            <?php
            if(!empty($task['Task']['lastdone'])) {
                echo h($task['Task']['lastdone']);
            } else {
                echo '&nbsp;';
            }
            ?>
    </dd>
    <?php endif; ?>
    <dt>通知：</dt>
    <dd>
        <?php
        if (count($task['Reminder']) > 0) {
            foreach($task['Reminder'] as $r) {
                if ($r['status'] == 0) {
                    if ($r['type'] == 0) {
                        echo '有効日の ' . date('H:i',strtotime($r['time'])) . "<br />\n";
                    } else if (time() < strtotime($r['datetime'])) {
                        echo date('Y/m/d H:i', strtotime($r['datetime'])) . "<br />\n";
                    }
                }
            }
        } else {
            echo '&nbsp;';
        }
        ?>
    </dd>
</dl>
