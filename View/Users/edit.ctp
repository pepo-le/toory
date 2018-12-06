<?php
$this->assign('title', 'ユーザー情報変更');
?>
<div>
    <?php
    echo $this->Form->create(
        'User',
        array(
            'type' => 'post',
            'class' => 'uk-form uk-form-horizontal c-form',
            'inputDefaults' => array(
                'error' => array(
                    'attributes' => array('class' => 'uk-alert uk-alert-danger uk-width-medium-1-2 c-form__error-message')
                )
            )
        )
    );
    ?>
    <?php if (!$twitter_register): ?>
        <div class="uk-form-row">
            <?php
            echo $this->Form->label(
                'User.username',
                'ログインID',
                array(
                    'class' => 'uk-form-label c-form__label--required'
                )
            );
            echo $this->Form->input(
                'User.username',
                array(
                    'label' => false,
                    'type' => 'text',
                    'style' => 'ime-mode: disabled',
                    'div' => array(
                        'class' => 'uk-form-controls'
                    )
                )
            );
            ?>
        </div>
        <div class="uk-form-row">
            <div class="uk-form-label"></div>
            <div class="uk-form-controls">
                <?php echo $this->Html->link('パスワードを変更する', '/users/password', array('class' => 'u-text-small')); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="uk-form-row">
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'User.screenname',
            '表示ユーザー名',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
            echo $this->Form->input(
                'User.screenname',
                array(
                    'label' => false,
                    'type' => 'text',
                    'div' => array(
                        'class' => 'uk-form-controls'
                    )
                )
            );
        ?>
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'User.email',
            'E-Mail',
            array(
                'class' => 'uk-form-label'
            )
        );
        echo $this->Form->input(
            'User.email',
            array(
                'label' => false,
                'type' => 'email',
                'div' => array(
                    'class' => 'uk-form-controls'
                )
            )
        );
        ?>
    </div>
    <?php if ($email && !$email_activation): ?>
        <div class="uk-form-row p-email-activation">
            <div class="uk-form-label"></div>
            <div class="uk-form-controls">
                <?php echo $this->Html->link('メールアドレスを確認する', '/users/mail_activate', array('class' => 'u-text-small')); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'User.changetime',
            '日付変更時刻',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'User.changetime',
            array(
                'label' => false,
                'type' => 'time',
                'div' => array(
                    'class' => 'uk-form-controls'
                ),
                'timeFormat' => 24
            )
        );
        ?>
    </div>
    <div class="uk-form-row">
        <?php
        // element
        echo $this->element('timezone_list');
        ?>
    </div>
    <?php if ($email_activation): ?>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'User.vacation',
            '休暇モード',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'User.vacation',
            array(
                'label' => 'すべての通知を無効にする',
                'type' => 'checkbox',
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox'
                )
            )
        );
        ?>
    </div>
    <?php endif; ?>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php
            echo $this->Form->button('変更', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php echo $this->Html->link('アカウントを削除する', '/users/delete', array('class' => 'uk-text-small')); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
