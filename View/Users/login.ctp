<?php
$this->assign('title', 'ログイン');
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
        <?php
        echo $this->Form->label(
            'User.password',
            'パスワード',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'User.password',
            array(
                'label' => false,
                'type' => 'password',
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
            <p class="uk-text-small">パスワードを忘れた方は<?php echo $this->Html->link('こちら', 'password_reset'); ?></p>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <?php
        echo $this->Form->input(
            'User.remember',
            array(
                'label' => '次回も自動でログイン',
                'type' => 'checkbox',
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox'
                )
            )
        );
        ?>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php
            echo $this->Form->button('ログイン', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php echo $this->Html->link('ユーザー登録はこちら', 'signup'); ?>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls c-form-controls--twitter">
            <?php echo $this->Html->link('<i class="fa fa-twitter" aria-hidden="true"></i>Twitterアカウントでログイン', 'oauthtwitter', array('class' => 'uk-button c-button--twitter', 'escape' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

