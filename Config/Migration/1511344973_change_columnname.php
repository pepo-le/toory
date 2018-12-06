<?php
class ChangeColumnname extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'change_columnname';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tasks' => array(
					'begin' => array('type' => 'date', 'null' => false, 'default' => null, 'after' => 'body'),
					'expire' => array('type' => 'date', 'null' => false, 'default' => null, 'after' => 'begin'),
				),
			),
			'drop_field' => array(
				'tasks' => array('start', 'end'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'tasks' => array('begin', 'expire'),
			),
			'create_field' => array(
				'tasks' => array(
					'start' => array('type' => 'date', 'null' => false, 'default' => null),
					'end' => array('type' => 'date', 'null' => false, 'default' => null),
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
