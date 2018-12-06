<!DOCTYPE html>
<html>
<head>
<?php echo $this->Html->charset(); ?>
<title>
    <?php echo $this->fetch('title') . '｜' . TITLE; ?>
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
<div id="container" class="p-content">
    <header id="header" class="header">
        <?php echo $this->element('navi'); ?>
    </header>
    <main id="content" class="uk-container uk-container-center content">
        <?php echo $this->element('breadcrumbs'); ?>
        <article class="uk-margin-top">
            <?php echo $this->Session->flash(); ?>
            <?php if(!empty($user) && !$user['User']['register'] && strpos(Router::url(), 'users') === false): ?>
                <section>
                    <div class="uk-alert uk-alert-warning c-alert">
                        <p>ゲストユーザーとして試用中です。登録は<?php echo $this->Html->link('こちら', '/users/signup'); ?></p>
                    </div>
                </section>
            <?php endif; ?>
            <?php echo $this->fetch('content'); ?>
        </article>
    </main>
    <footer id="footer" class="footer p-footer">
        <div class="uk-container uk-container-center">
            <div class="uk-navbar p-nav">
                <div class="uk-navbar-content uk-navbar-flip">
                    <span><?php echo TITLE; ?></span>
                </div>
                <div class="uk-navbar-content uk-navbar-flip">
                    <span><?php echo $this->Html->link('お問い合わせ', '/contact'); ?></span>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
