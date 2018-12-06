<?php
// 現在のパスを取得
$root_path = substr(Router::url('/'), 0, -1);
$current_path = str_replace(Router::url('/'), '/', Router::url());

$nav_arr;
$nav_header = $this->Html->image('logo_white.png');
if (!empty($user)) {
    $nav_arr = array(
        '/tasks' => '<i class="fa fa-list-alt" aria-hidden="true"></i>今日のタスク',
        '/tasks/todo/' => array(
            'parent' => '<i class="fa fa-check-square-o" aria-hidden="true"></i>Todo',
            'children' => array(
                '/tasks/todo/' => 'これからのTodo',
                '/tasks/todo/done' => '過去のTodo'
            )
        ),
        '/tasks/routine/' => array(
            'parent' => '<i class="fa fa-repeat" aria-hidden="true"></i>ルーチン',
            'children' => array(
                '/tasks/routine/' => '現在のルーチン',
                '/tasks/routine/forward' => '将来のルーチン',
                '/tasks/routine/done' => '過去のルーチン'
            )
        ),
        '/histories/' => array(
            'parent' => '<i class="fa fa-bar-chart p-side-navi__icon--square" aria-hidden="true"></i>履歴',
            'children' => array(
                '/histories/' => '履歴カレンダー',
                '/histories/all' => '履歴一覧',
                '/histories/year' => '年別集計',
                '/histories/month' => '月別集計',
                '/histories/week' => '週別集計',
                '/histories/graph' => '履歴グラフ'
            )
        ),
    );

    if ($user['User']['email_activation']) {
        $nav_arr['/reminders'] = '<i class="fa fa-bell p-side-navi__icon--square" aria-hidden="true"></i>リマインダー';
    }
} else {
    $nav_arr['/tasks'] = '<i class="fa fa-check-square-o" aria-hidden="true"></i>使ってみる';
}
$nav_arr_footer;
if (!empty($user) && $user['User']['register']) {
    $nav_arr_footer['/users/edit'] = '<i class="fa fa-user" aria-hidden="true"></i>' . $user['User']['screenname'];
    $nav_arr_footer['/users/logout'] = '<i class="fa fa-sign-out" aria-hidden="true"></i>ログアウト';
} else {
    $nav_arr_footer['/users/signup'] = '<i class="fa fa-user-plus" aria-hidden="true"></i>ユーザー登録する';
    $nav_arr_footer['/users/login'] = '<i class="fa fa-sign-in" aria-hidden="true"></i>ログイン';
}
?>
<div id="side-navi" class="uk-offcanvas p-offcanvas">
    <div class="uk-offcanvas-bar p-side-navi">
        <ul class="uk-nav uk-nav-parent-icon p-side-navi__header" data-uk-nav>
            <?php
            echo '<li class="uk-nav-header">' . $this->Html->link($nav_header, '/', array('escape' => false)) . '</li>';
            ?>
        </ul>
        <?php if (!empty($user)): ?>
            <ul class="uk-nav p-side-navi__new-task">
                <li><?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>タスクを作成', '/tasks/create', array('escape' => false)); ?></li>
            </ul>
        <?php endif; ?>
        <ul class="uk-nav uk-nav-parent-icon" data-uk-nav>
            <?php
            foreach($nav_arr as $path => $item) {
                if (is_array($item)) {
                    if(strpos($current_path, $path) !== false) {
                        // 親のパスを含む場合
                        echo '<li class="uk-parent p-side-navi-parent-active"><a href="#">' . $item['parent'] . '</a>';
                    } else {
                        echo '<li class="uk-parent"><a href="#">' . $item['parent'] . '</a>';
                    }
                    echo '<ul class="uk-nav-sub">';
                        foreach($item['children'] as $child_path => $child_item) {
                            if ($child_path === $current_path) {
                                echo '<li class="uk-active p-nav-sub-list p-side-navi-active">' . $this->Html->link($child_item, $child_path) . '</li>';
                            } else {
                                echo '<li class="p-nav-sub-list">' . $this->Html->link($child_item, $child_path) . '</li>';
                            }
                        }
                    echo '</ul>';
                    echo '</li>';

                    continue;
                }

                if ($path === $current_path) {
                    echo '<li class="p-side-navi-active">' . $this->Html->link($item, $path, array('escape' => false)) . '</li>';
                } else {
                    echo '<li>' . $this->Html->link($item, $path, array('escape' => false)) . '</li>';
                }
            }
            ?>
        </ul>
        <ul class="uk-nav uk-nav-parent-icon p-side-navi__footer" data-uk-nav>
            <?php
            foreach($nav_arr_footer as $path => $item) {
                if ($path === $current_path) {
                    echo '<li class="p-side-navi-active">' . $this->Html->link($item, $path, array('escape' => false)) . '</li>';
                } else {
                    echo '<li>'. $this->Html->link($item, $path, array('escape' => false)) . '</li>';
                }
            }
            ?>
        </ul>
    </div>
</div>
