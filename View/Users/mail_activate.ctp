<?php
$this->assign('title', 'メールアドレス確認');
$this->Html->addCrumb('ユーザー情報変更', '/users/edit');
$this->Html->addCrumb('メールアドレス確認');
?>
<?php if ($sended): ?>
<div>
    <p>登録メールアドレスに確認メールを送信済みです。</p>
    <p>確認メールは1時間に一度のみ送信できます。</p>
</div>
<?php else: ?>
<div>
    <p>登録メールアドレスに確認メールを送信しました。</p>
    <p>メール内のURLをクリックしてメールアドレスの登録を完了してください。</p>
</div>
<?php endif; ?>
