<?php
$this->assign('title', 'パスワード再設定メール送信');
$this->Html->addCrumb('パスワード再設定', '/users/password_reset');
$this->Html->addCrumb('再設定メール送信');
?>
<?php if ($sended): ?>
<div>
    <p>登録メールアドレスにパスワード再設定メールを送信済みです。</p>
    <p>再設定メールは1時間に一度のみ送信できます。</p>
</div>
<?php else: ?>
<div>
    <p>登録メールアドレスにパスワード再設定メールを送信しました。</p>
    <p>メール内のURLをクリックしてパスワードの再設定を完了してください。</p>
</div>
<?php endif; ?>
