<?php
class AddIndexes extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_indexes';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'auto_logins' => array('indexes' => array('token')),
			),
			'alter_field' => array(
				'histories' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
				'reminders' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'task_id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'tasks' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
					'begin' => array('type' => 'date', 'null' => false, 'default' => null, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'histories' => array(
					'indexes' => array(
						'user_id' => array('column' => array('user_id', 'date'), 'unique' => 1),
					),
				),
				'reminders' => array(
					'indexes' => array(
						'user_id' => array('column' => 'user_id', 'unique' => 0),
						'task_id' => array('column' => 'task_id', 'unique' => 0),
					),
				),
				'tasks' => array(
					'indexes' => array(
						'begin' => array('column' => 'begin', 'unique' => 0),
						'user_id' => array('column' => 'user_id', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'auto_logins' => array(
					'indexes' => array(
						'token' => array('column' => 'token', 'unique' => 1),
					),
				),
			),
			'alter_field' => array(
				'histories' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
				'reminders' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'task_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
				'tasks' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'begin' => array('type' => 'date', 'null' => false, 'default' => null),
				),
			),
			'drop_field' => array(
				'histories' => array('indexes' => array('user_id')),
				'reminders' => array('indexes' => array('user_id', 'task_id')),
				'tasks' => array('indexes' => array('begin', 'user_id')),
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
