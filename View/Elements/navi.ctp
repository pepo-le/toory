<div class="uk-container uk-container-center">
    <nav class="uk-navbar p-nav p-nav--header">
        <a href="#side-navi" class="uk-navbar-toggle p-offcanvas-toggle" data-uk-offcanvas="{mode:'push'}"></a>
        <?php echo $this->element('side_navi'); ?>
        <div id="logo" class="uk-navbar-content p-nav-logo">
            <?php echo $this->Html->link('', '/'); ?>
        </div>
        <div class="uk-navbar-flip uk-hidden-small">
            <ul class="uk-navbar-nav p-nav__list">
                <li>
                    <?php echo $this->Html->link(
                        '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>タスクを作成',
                        '/tasks/create',
                        array('escape' => false)
                    ); ?>
                </li>
                <li class="uk-parent" data-uk-dropdown>
                <?php if (!empty($user) && $user['User']['register'] !== false): ?>
                    <?php echo $this->Html->link('<i class="fa fa-user-circle" aria-hidden="true"></i>' . h($user['User']['screenname']), '/users/edit', array('escape' => false)); ?>
                <?php else: ?>
                    <a href="/users/login"><i class="fa fa-user-circle" aria-hidden="true"></i>ゲストさん</a>
                <?php endif; ?>
                    <div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom">
                        <ul class="uk-nav uk-nav-navbar p-nav__dropdown">
                            <?php if (!empty($user) && $user['User']['register']): ?>
                                <li><?php echo $this->Html->link('ユーザー情報変更', '/users/edit'); ?></li>
                                <li><?php echo $this->Html->link('ログアウト', '/users/logout'); ?></li>
                            <?php else: ?>
                                <li><?php echo $this->Html->link('ユーザー登録', '/users/signup'); ?></li>
                                <li><?php echo $this->Html->link('ログイン', '/users/login'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="uk-navbar-content uk-navbar-center p-nav__title">
            <h1><?php echo $this->fetch('title'); ?></h1>
        </div>
    </nav>
</div>
