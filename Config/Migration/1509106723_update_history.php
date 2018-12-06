<?php
class UpdateHistory extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'update_history';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'task_histories' => array(
					'done_todo' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'date'),
					'done_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'done_todo'),
					'total_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'after' => 'done_routine'),
				),
			),
			'drop_field' => array(
				'task_histories' => array('done_count'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'task_histories' => array('done_todo', 'done_routine', 'total_routine'),
			),
			'create_field' => array(
				'task_histories' => array(
					'done_count' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
