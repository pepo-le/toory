<?php
$this->assign('title', 'ユーザー登録');
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
        <?php
        echo $this->Form->label(
            'User.password_retype',
            'パスワード（再入力）',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'User.password_retype',
            array(
                'label' => false,
                'type' => 'password',
                'id' => 'UserPasswordRetype',
                'errorMessage' => false,
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
    <div class="uk-form-row">
        <?php
        // element
        echo $this->element('timezone_list');
        ?>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <?php
        echo $this->Form->input(
            'User.remember',
            array(
                'label' => '自動でログイン',
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
            echo $this->Form->button('登録', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php echo $this->Html->link('ログインはこちら', 'login'); ?>
        </div>
    </div>
    <div class="uk-form-row">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls c-form-controls--twitter">
            <?php echo $this->Html->link('<i class="fa fa-twitter" aria-hidden="true"></i>Twitterアカウントでユーザー登録', 'oauthtwitter', array('class' => 'uk-button c-button--twitter', 'escape' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
