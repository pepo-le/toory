<?php
$this->assign('title', '履歴の編集');
$this->Html->addCrumb('履歴', '/histories/');
$this->Html->addCrumb('履歴の編集');
?>
<div>
    <?php
    echo $this->Form->create(
        'History',
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
    <?php
        echo $this->Form->input(
            'History.date',
            array(
                'label' => false,
                'type' => 'hidden',
            )
        );
    ?>
    <div class="uk-form-row">
    <?php
        echo $this->Form->label(
            'History.todo',
            'Todo実行数',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'History.todo',
            array(
                'label' => false,
                'type' => 'number',
                'max' => 99,
                'min' => 0,
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
            'History.routine',
            'ルーチン実行数',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'History.routine',
            array(
                'label' => false,
                'type' => 'number',
                'max' => 99,
                'min' => 0,
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
            'History.total',
            'ルーチン総数',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'History.total',
            array(
                'label' => false,
                'type' => 'number',
                'max' => 99,
                'min' => 0,
                'div' => array(
                    'class' => 'uk-form-controls'
                )
            )
        );
    ?>
    </div>
    <div class="uk-form-row c-form__submit">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php
            echo $this->Form->button('変更する', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
            <?php
            echo $this->Form->button('履歴を削除する', array(
                'name' => 'delete',
                'type' => 'submit',
                'class' => 'uk-button uk-margin-left c-button--danger'
            ));
            ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
