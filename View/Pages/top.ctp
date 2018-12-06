<!DOCTYPE html>
<html>
<head>
<?php echo $this->Html->charset(); ?>
<title>
    <?php $this->assign('title', 'Todoとルーチンワークを一緒にチェック'); ?>
    <?php echo TITLE . '｜' . $this->fetch('title'); ?>
</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
<?php
echo $this->Html->meta('icon');

echo $this->Html->css('/css/vendor/normalize.css');
echo $this->Html->css('/css/vendor/uikit.almost-flat.min.css');
echo $this->Html->css('/css/vendor/font-awesome.min.css');
echo $this->Html->css('style.css');

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<?php echo $this->Html->script('//cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js'); ?>
<?php echo $this->Html->script('/js/vendor/uikit.min.js'); ?>
<?php echo $this->Html->script('script.js');?>
</head>
<body>
<div id="container">
    <header id="header" class="header p-top-header">
    <div class="uk-container uk-container-center">
        <nav class="uk-navbar p-nav p-nav--header">
            <div class="uk-navbar-flip p-top-nav">
                <ul class="uk-navbar-nav p-top-nav__list">
                    <li><?php echo $this->Html->link('ログイン', '/users/login'); ?></li>
                    <li><?php echo $this->Html->link('ユーザー登録', '/users/signup'); ?></li>
                </ul>
            </div>
            <div id="logo" class="uk-navbar-content uk-navbar-center p-top-logo">
                <?php echo $this->Html->link('', '/'); ?>
            </div>
        </nav>
    </div>
    </header>

    <main id="content" class="content">
        <article class="p-top-main">
            <section class="uk-container uk-container-center">
                <div class="p-top-main__left">
                    <?php echo $this->Session->flash(); ?>
                    <p><?php echo $this->Html->image('logo_white.png', array('class' => 'p-top-main__logo')); ?></p>
                    <h1>毎日のタスクマネジメント</h1>
                    <p class="p-top-main__text">Todoとルーチンをまとめて確認。</p>
                    <p><?php echo $this->Html->link('<span class="uk-button p-top-button">使ってみる</span>', '/tasks', array('escape' => false)); ?></p>
                    <p class="p-top-main__login-text">アカウントをお持ちの方は<?php echo $this->Html->link('ログイン', '/users/login'); ?></p>
                </div>
            </section>
            <div class="p-top-main__right">
            </div>
        </article>
        <div class="uk-container uk-container-center p-top-detail">
            <article class="uk-grid">
                <section class="uk-width-medium-1-3 p-top-detail__inner">
                    <?php echo $this->Html->image('check.svg'); ?>
                    <h2>Todoとルーチンワーク</h2>
                    <p>期限の決まっているTodoや、継続的に行いたいルーチンワークをまとめてチェックすることで、一日の時間を有効に活用できます。</p>
                </section>
                <section class="uk-width-medium-1-3 p-top-detail__inner">
                    <?php echo $this->Html->image('clock.svg'); ?>
                    <h2>リマインダー</h2>
                    <p>Todoやルーチンワークの開始をメールで通知することができます。定期的な通知や、日時を指定した通知が可能です</p>
                </section>
                <section class="uk-width-medium-1-3 p-top-detail__inner">
                    <?php echo $this->Html->image('graph.svg'); ?>
                    <h2>実行履歴を記録</h2>
                    <p>タスクの実行履歴を記録できます。カレンダー表示や、月ごとの集計などをチェックして、習慣の改善に役立てることができます。</p>
                </section>
            </article>
            <aside>
                <div class="p-top-bottom-button">
                    <p><a href="/tasks"><span class="uk-button p-top-button p-top-button--bottom">使ってみる</span></a></p>
                </div>
            </aside>
        </div>
    </main>
    </div>
    <footer id="footer" class="footer p-top-footer">
        <div class="uk-container uk-container-center">
            <div class="uk-navbar p-nav">
                <div class="uk-navbar-content uk-navbar-flip">
                    <span><?php echo TITLE; ?></span>
                </div>
                <div class="uk-navbar-content uk-navbar-flip">
                    <span>お問い合わせ</span>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
