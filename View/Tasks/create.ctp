<?php
$this->assign('title', '新規タスク作成');
?>
<div>
    <?php
    echo $this->Form->create(
        'Task', array(
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
            'Task.tasktype_id',
            '種類',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Task.tasktype_id',
            array(
                'type' => 'radio',
                'legend' => false,
                'default' => 0,
                'options' => array('Todo', 'Routine'),
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text c-form__radio'
                )
            )
        );
        ?>
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'Task.title',
            'タスク名',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Task.title',
            array(
                'label' => false,
                'type' => 'text',
                'class' => 'uk-width-1-1',
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
            'Task.color',
            'タスクカラー',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Task.color',
            array(
                'label' => false,
                'type' => 'color',
                'default' => '#5599ee',
                'list' => 'color-list',
                'class' => 'uk-width-1-4',
                'div' => array(
                    'class' => 'uk-form-controls'
                )
            )
        );
        ?>
        <datalist id="color-list">
            <option value="#3498db"></option>
            <option value="#2ecc71"></option>
            <option value="#f1c40f"></option>
            <option value="#e67e22"></option>
            <option value="#e74c3c"></option>
            <option value="#9b59b6"></option>
            <option value="#34495e"></option>
            <option value="#444444"></option>
            <option value="#bdc3c7"></option>
            <option value="#ecf0f1"></option>
        </datalist>
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'Task.body',
            'タスク詳細',
            array(
                'class' => 'uk-form-label'
            )
        );
        echo $this->Form->input(
            'Task.body',
            array(
                'label' => false,
                'type' => 'textarea',
                'class' => 'uk-width-1-1',
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
            'Task.day',
            '曜日',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        echo $this->Form->input(
            'Task.day',
            array(
                'label' => false,
                'select',
                'multiple' => 'checkbox',
                'options' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
                'selected' => array(0, 1, 2, 3, 4, 5, 6),
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox'
                )
            )
        );
        ?>
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'Task.begin',
            '開始日',
            array(
                'class' => 'uk-form-label c-form__label--required'
            )
        );
        ?>
        <?php
        echo $this->Form->input(
            'Task.begin',
            array(
                'label' => false,
                'type' => 'date',
                'dateFormat' => 'YMD',
                'monthNames' => false,
                'minYear' => date('Y'),
                'orderYear'=>'asc',
                'default' => $date,
                'separator' => ' / ',
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text'
                )
            )
        );
        ?>
    </div>
    <div class="uk-form-row">
    </div>
    <div class="uk-form-row">
        <?php
        echo $this->Form->label(
            'Task.expire',
            '有効期限',
            array(
                'class' => 'uk-form-label'
            )
        );
        ?>
        <?php
        echo $this->Form->input(
            'Task.expirecheck',
            array(
                'label' => false,
                'select',
                'multiple' => 'checkbox',
                'options' => array('タスクの有効期限を決める'),
                'id' => 'expire-check',
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox'
                )
            )
        );
        ?>
        <?php
        echo $this->Form->input(
            'Task.expire',
            array(
                'label' => false,
                'type' => 'date',
                'dateFormat' => 'YMD',
                'monthNames' => false,
                'minYear' => date('Y'),
                'orderYear'=>'asc',
                'default' => date('Y-m-d', strtotime('+1 month', strtotime($date))),
                'separator' => ' / ',
                'class' => 'expire',
                'div' => array(
                    'class' => 'uk-form-controls uk-form-controls-text'
                )
            )
        );
        ?>
    </div>
    <?php if ($email_activation): ?>
        <div class="uk-form-row">
            <?php
            echo $this->Form->label(
                'Task.reminder',
                'リマインダー',
                array(
                    'class' => 'uk-form-label'
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'Task.reminder_timecheck',
                array(
                    'label' => false,
                    'select',
                    'multiple' => 'checkbox',
                    'id' => 'reminder-time-check',
                    'options' => array('有効日の指定時刻に通知'),
                    'div' => array(
                        'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox'
                    )
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'Task.reminder_time',
                array(
                    'label' => false,
                    'type' => 'time',
                    'div' => array(
                        'class' => 'uk-form-controls'
                    ),
                    'timeFormat' => 24,
                    'interval' => 30,
                    'default' => '9:0',
                    'class' => 'reminder-time',
                    'div' => array(
                        'class' => 'uk-form-controls uk-form-controls-text'
                    )
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'Task.reminder_datetimecheck',
                array(
                    'label' => false,
                    'select',
                    'multiple' => 'checkbox',
                    'id' => 'reminder-datetime-check',
                    'options' => array('指定日時に通知'),
                    'div' => array(
                        'class' => 'uk-form-controls uk-form-controls-text c-form__checkbox c-form__datetime-check'
                    )
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'Task.reminder_datetime',
                array(
                    'label' => false,
                    'type' => 'date',
                    'dateFormat' => 'YMD',
                    'monthNames' => false,
                    'minYear' => date('Y'),
                    'orderYear'=>'asc',
                    'separator' => ' / ',
                    'default' => date('Y-m-d', strtotime('+1 day', strtotime($date))),
                    'class' => 'reminder-datetime',
                    'error' => false,
                    'div' => array(
                        'class' => 'uk-form-controls uk-form-controls-text c-form__datetime'
                    )
                )
            );
            ?>
            <?php
            echo $this->Form->input(
                'Task.reminder_datetime',
                array(
                    'label' => false,
                    'type' => 'time',
                    'timeFormat' => '24',
                    'interval' => 30,
                    'default' => ' 9:0',
                    'class' => 'reminder-datetime',
                    'error' => array(
                        'attributes' => array('class' => 'uk-form-controls uk-alert uk-alert-danger uk-width-medium-1-2 c-form__error-message')
                    ),
                    'div' => array(
                        'class' => 'uk-form-controls-text c-form__datetime c-form__datetime--time'
                    )
                )
            );
            ?>
        </div>
    <?php else: ?>
        <div class="uk-form-row">
            <?php
            echo $this->Form->label(
                'Task.reminder',
                'リマインダー',
                array(
                    'class' => 'uk-form-label'
                )
            );
            ?>
            <?php if ($register): ?>
                <div class="uk-form-controls uk-form-controls-text">
                    <p>リマインダーのご利用にはメールアドレスの確認が必要です。</p>
                </div>
            <?php else: ?>
                <div class="uk-form-controls uk-form-controls-text">
                    <p>リマインダーのご利用にはユーザー登録とメールアドレスの確認が必要です。</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="uk-form-row c-form__submit">
        <div class="uk-form-label"></div>
        <div class="uk-form-controls">
            <?php
            echo $this->Form->button('タスクを作成する', array(
                'type' => 'submit',
                'class' => 'uk-button c-button--primary uk-button-large'
            ));
            ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
