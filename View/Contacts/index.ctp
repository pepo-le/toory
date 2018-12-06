<?php
$this->assign('title', 'お問い合わせ');
?>
<div>
    <?php
    echo $this->Form->create(
        'Contact',
        array(
            'type' => 'post',
            'class' => 'uk-form uk-form-horizontal c-form',
            'inputDefaults' => array(
                'error' => array(
                    'attributes' => array('class' => 'uk-alert uk-alert-danger uk-width-1-2 c-form__error-message')
                )
            )
        )
    );
    ?>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'Contact.name',
            'お名前',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Contact.name',
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
            'Contact.email',
            '返信先メールアドレス',
            array(
                'class' => 'uk-form-label'
            )
        );
        echo $this->Form->input(
            'Contact.email',
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
        echo $this->Form->label(
            'Contact.body',
            'お問い合わせ内容',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Contact.body',
            array(
                'label' => false,
                'type' => 'textarea',
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
