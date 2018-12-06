<?php
$this->assign('title', '履歴カレンダー');
$this->assign('css', '<link rel="stylesheet" type="text/css" href="/css/vendor/loaders.css">');
?>
<div class="p-calendar">
    <?php
    // カレンダーを表示
    echo $histories_table;
    ?>
    <div class="p-calendar__note">
        <p><i class="fa fa-check-square-o p-calendar__table__day-icon" aria-hidden="true"></i>：Todo実行数、<i class="fa fa-circle-o-notch p-calendar__table__day-icon" aria-hidden="true"></i>：ルーチン実行数／ルーチン総数、<i class="fa fa-percent p-calendar__table__day-icon" aria-hidden="true"></i>：ルーチン実行率</p>
    </div>
    <div class="uk-text-right">
        <?php echo $this->Html->link('履歴を全て削除する', '/histories/delete_all/', array('class' => 'uk-button c-button--danger')); ?>
    </div>
</div>
