<?php
class RenameHistories extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'rename_histories';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'histories' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'date' => array('type' => 'date', 'null' => false, 'default' => null),
					'done_todo' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'done_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'total_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
			'drop_table' => array(
				'task_histories'
			),
		),
		'down' => array(
			'drop_table' => array(
				'histories'
			),
			'create_table' => array(
				'task_histories' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
					'date' => array('type' => 'date', 'null' => false, 'default' => null),
					'done_todo' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'done_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'total_routine' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
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
