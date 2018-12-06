<?php
$this->assign('title', 'パスワード再設定フォーム');
$this->Html->addCrumb('パスワード再設定', '/users/password_reset');
$this->Html->addCrumb('再設定フォーム');
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
        <div class="uk-form-label">ログインID</div>
        <div class="uk-form-controls uk-form-controls-text">
            <p><b><?php echo h($username); ?></b></p>
        </div>
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'User.password',
            '新しいパスワード',
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
            '新しいパスワード（再入力）',
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
    <?php echo $this->Form->end(); ?>
</div>
