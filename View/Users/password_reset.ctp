<?php
$this->assign('title', 'パスワード再設定');
?>
<div>
    <p>登録済みのログインIDとE-Mailアドレスを入力してください。</p>
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
            'User.email',
            'E-Mail',
            array(
                'class' => 'uk-form-label c-form__label--required'
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
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php
            echo $this->Form->button('送信', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
